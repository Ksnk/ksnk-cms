<?php

/**
 * Класс для кэширования данных в файловую систему.
 *
 * Назначение:
 *   Кэширование веб-страниц в проектах, где есть повышенные требования к надёжности и скорости.
 *   Класс м.б. использован в проектах со средней посещаемостью и нагрузкой.
 *   Для высоконагруженных систем обычно используют Memcache/MemcacheDB.
 *
 * Отличительные особенности:
 *   * отслеживаение зависимых файлов по времени модификации
 *   * эффективная работа при одновременном использовании и перестроении кэша несколькими процессами
 *   * возможность получения устаревших данных кэша
 *   * получение из кэша заголовков Last-Modified, Content-Type
 *   * методы для удаления файлов кэша для пользователя, группы или всего кэша целиком
 *   * автоматическое удаление устаревших файлов из кэша
 *
 * При получении данных из кэша они считаются не действительными если:
 *   * файла кэша нет/пустой/разбитый или время жизни кэша истекло
 *   * какого-либо зависимого файла уже нет или время его модификации изменилось
 *
 * @license  http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @author   Nasibullin Rinat, http://orangetie.ru/
 * @charset  ANSI
 * @version  1.2.2
 */
class FileCache
{
     var $dependent_files = array(); #ассоц. массив зависимых файлов, где ключами явл. имена, а значениями время модификации в unixtime
     var $filename = null;           #полное имя файла, устанавливается в конструкторе
     var $fp = null;                 #ресурс для fopen()

    #опции конструктора:
     var $_dir = '/tmp/';      #директория для хранения кэшированных файлов
     var $_ttl = 10800;        #время жизни файлов в кэше
     var $_cleaning_probability = 100;  #коэффицент для автоматического удаления кэша (1 из X)
     var $_group = '';         #группа страницы, строка
     var $_user_id = 0;        #идентификатор текущего пользователя, число (ноль для анонимного пользователя)
     var $_hash = '';          #хэш (уникальный идентификатор) страницы, строка
     var $_is_enabled = true;  #кэширование включено?

    /**
     * Конструктор
     *
     * @param   array     $options  массив опций  (см. выше область описания переменных)
     */
     function FileCache( $options)
    {
        $this->setOptions($options);
        $this->filename = preg_replace('~[/\\\\]+~s', '/', $this->_dir . '/cache_' . intval($this->_user_id) . '_' . $this->_group . '_' . $this->_hash);
    }
/*
     function __destruct()
    {
        #запускаем автоматическое удаление устаревших файлов с вероятностью 1 из X
        if ($this->_is_enabled
            && $this->_cleaning_probability > 0
            && rand(1, $this->_cleaning_probability) == 1) $this->clean($is_check_ttl = true);
    }
*/
    /**
     * Устанавливает опции
     *
     * @param   array     $options  массив опций  (см. выше область описания переменных класса)
     * @return  void
     */
     function setOptions( $options)
    {
        #if (! is_array($options)) trigger_error('An array type expected in first parameter, ' . gettype($options) . ' given!', E_USER_ERROR);
        foreach ($options as $k => $v)
        {
            $p = '_' . $k;
            if (isset($this->$p)) $this->$p =&$options[$k];
            else trigger_error('Unknown option "' . $k . '" !', E_USER_ERROR);
        }#foreach
    }

    /**
     * Если параметр указан, добавляет файлы, от времени модификации которых зависит закэшированная страница.
     * Если параметр не указан, возвращает список файлов.
     * Имена файлов указываются с полными путями.
     *
     * @param   mixed(/string/null)  $files
     * @return  mixed(/bool)
     */
     function dependentFiles($files = null)
    {
        if ($files === null) return $this->dependent_files;
        if (! $this->_is_enabled) return false;
        if (! is_array($files)) $files = array($files);
        foreach ($files as $file)
        {
            $file = preg_replace('~[/\\\\]+~s', '/', $file);
            if (  ! array_key_exists($file, $this->dependent_files)
                  #файла может уже не быть в файловой системе, поэтому ставим @
                  && ($mtime = @filemtime($file)) !== false
               )  $this->dependent_files[$file] = pack('V', $mtime);
        }#foreach
        return true;
    }

    #удаляет все или устаревшие файлы из кэша
    #возвращает кол-во удалённых файлов
     function clean($is_check_ttl = false)
    {
        #страницы, которые чаще используют -- первые на удаление
        return $this->_clean($this->_user_id, '*', $is_check_ttl) +   #сначала для пользователя
               $this->_clean('*', $this->_group, $is_check_ttl)   +   #потом для группы
               $this->_clean('*', '*', $is_check_ttl);                #и только потом всё остальное
    }

    #удаляет все или устаревшие файлы из кэша для текущего пользователя
    #возвращает кол-во удалённых файлов
     function cleanForUser($is_check_ttl = false)
    {
        return $this->_clean($this->_user_id, '*', $is_check_ttl);
    }

    #удаляет все или устаревшие файлы из кэша для текущей группы
    #возвращает кол-во удалённых файлов
     function cleanForGroup($is_check_ttl = false)
    {
        return $this->_clean('*', $this->_group, $is_check_ttl);
    }

    /**
     * Возвращает содержимое закэшированного файла или FALSE,
     * если кэширование отключено, файл не найден/пустой/битый, кэш устарел.
     *
     * @param   mixed(int/null)        &$last_modified       last modification time in unixtime
     * @param   mixed(string/null)     &$content_type
     * @param   bool                   $is_check_ttl        проверять актуальность кэша?
     * @param   bool                   $is_check_depends    проверять актуальность зависимых файлов?
     * @return  mixed(string/false)
     */
     function read(&$last_modified , &$content_type , $is_check_ttl = true, $is_check_depends = true)
    {
        if (! $this->_is_enabled) return false;

        #вначале создаем пустой файл, ЕСЛИ ЕГО ЕЩЕ НЕТ; если же файл существует, это его не разрушит
        if ($fp = @fopen($this->filename, 'a+b')) fclose($fp);
        else return false;

        #открываем файл
        $this->fp = @fopen($this->filename, 'r+b');
        if (! $this->fp) return false;  #нет прав доcтупа или файл удалён в другом процессе

        $data = $this->_read($fstat);
        $content = $this->_content($data, $fstat, $last_modified, $content_type, $is_check_ttl, $is_check_depends);
        if ($content !== false) return $content;

        #в этой точке мы понимаем, что кэша нет и содержимое веб-страницы нужно генерировать,
        #поэтому ставим эксклюзивную блокировку LOCK_EX (писать в файл одновременно может только один процесс)
        $flock_waiting_sec = 2;
        $flock_ping_sec    = 0.1;
        $flock_iterations  = $flock_waiting_sec / $flock_ping_sec;
        $flock_counter     = 0;
        $is_lock_ex        = false;

        #ждём, пока мы не станем единственными в течение нескольких секунд, слишком долгое ожидание раздражает клиента
        while ($flock_counter < $flock_iterations)
        {
            if (flock($this->fp, LOCK_EX + LOCK_NB)) #сразу возвращаем управление, используя LOCK_NB
            {
                $is_lock_ex = true;
                break;
            }
            else usleep($flock_ping_sec * 1000000); #ничего не делаем, давая возможность другим процессам поскорее завершить работу
            $flock_counter++;
        }#while

        #если заблокировать файл не удалось, пытаемся возвратить старые данные из кэша
        if (! $is_lock_ex) return $this->_content($data, $fstat, $last_modified, $content_type, $is_check_ttl = false, $is_check_depends = false);
        #если удалось заблокировать файл с первой попытки, то другой процесс в файл не пишет
        if ($flock_counter === 0) return false;
        #иначе пробуем ещё раз получить данные кэша, сделанные другим процессом
        $data = $this->_read($fstat, $is_lock_ex);
        return $this->_content($data, $fstat, $last_modified, $content_type, $is_check_ttl, $is_check_depends);
    }

     function _read(&$fstat, $is_lock_ex = false)
    {
        #используем разделяемую блокировку LOCK_SH для чтения кэша (читать из файла одновременно могут много процессов)
        #будем ждать окончание эксклюзивной блокировки в других процессах, которые пишут в файл
        if (! flock($this->fp, LOCK_SH)) return false;
        if ($is_lock_ex) fseek($this->fp, 0, SEEK_SET);
        $fstat = fstat($this->fp);
        if (! $fstat['size']) return false;
        return fread($this->fp, $fstat['size']);
    }

     function _content($data, $fstat, &$last_modified , &$content_type , $is_check_ttl = true, $is_check_depends = true)
    {
        if ($data === false || strlen($data) === 0) return false; #файл пустой
        if ($is_check_ttl && time() > $fstat['mtime'] + $this->_ttl) return false;  #истекло время актуальности файла

        $data = @unserialize($data);
        if (! is_array($data)) return false;    #битые сериализованные данные

        list($content_type, $gz_dependent_files, $content) = $data;
        unset($data);

        #проверяем актуальность зависимых файлов
        if ($is_check_depends)
        {
            #распаковываем и десериализуем список зависимых файлов:
            $dependent_files = @gzuncompress($gz_dependent_files);
            if ($dependent_files === false) return false;  #битые распакованные данные
            $dependent_files = @unserialize($dependent_files);
            if (! is_array($dependent_files)) return false; #битые сериализованные данные

            foreach ($dependent_files as $file => $Vmtime)
            {
                #файла может уже не быть в файловой системе, поэтому ставим @
                if (($mtime = @filemtime($file)) === false) return false; #файл не существует
                if (pack('V', $mtime) !== $Vmtime) return false;          #файл был модифицирован
            }#foreach
            $this->dependent_files = $dependent_files;
        }
        $last_modified = $fstat['mtime'];
        return $content;
    }

    /**
     * Записывает кэшированные данные в файл.
     * Возвращает кол-во записанных байт или FALSE, если кэширование отключено
     *
     * @param   string    $s
     * @param   string    $content_type       например: 'text/html', 'text/xml', ...
     * @return  mixed(int/false)
     */
     function write($s, $content_type = null)
    {
        if (! $this->_is_enabled) return false;
        ksort($this->dependent_files);  #optimization for gzcompress()
        $data = array(
            $content_type,
            gzcompress(serialize($this->dependent_files), 9),
            $s,
        );
        $data = serialize($data);
        $bytes = 0;

        if (! is_resource($this->fp)) return false;
        ftruncate($this->fp, 0);
        fseek($this->fp, 0, SEEK_SET);
        $bytes = fwrite($this->fp, $data);
        fflush($this->fp);
        flock($this->fp, LOCK_UN);
        fclose($this->fp);
        return $bytes;
    }

    /**
     * Удаляет устаревшие или все файлы из кэша.
     * При $is_check_ttl = true файлы удаляются с ограничением по времени работы, чтобы не заставлять клиента долго ждать
     *
     * @param   mixed(null/string)  $group         если группа указана, то будут удалены файлы только для этой группы
     * @param   bool                $is_check_ttl  проверять время жизни файлов?
     * @return  int                                возвращает кол-во удаленных файлов
     */
     function _clean($user_id = '*', $group = '*', $is_check_ttl = false)
    {
        #if (! is_scalar($user_id) && $user_id !== null) trigger_error('A scalar/null type expected in first parameter, '  . gettype($user_id) . ' type given!', E_USER_ERROR);
        #if (! is_string($group))   trigger_error('A string type expected in second parameter, ' . gettype($group)   . ' type given!', E_USER_ERROR);

        static $exec_time_max = null;  #время в unixtime с микросекундами, до которого скрипт удаления может выполняться, при $is_check_ttl = true

        if ($is_check_ttl)
        {
            if ($exec_time_max === null) $exec_time_max = microtime(true) + 2;
            elseif (microtime(true) > $exec_time_max) break;

            if ($user_id !== '*')   $pattern_re = '~^cache_' . intval($user_id) . '_[^_]*+_~siS';
            elseif ($group !== '*') $pattern_re = '~^cache_(?!' . intval($this->_user_id) . '_)\d++_' . $group . '_~siS';
            else $pattern_re = '~^cache_(?!' . intval($this->_user_id) . '_)\d++_(?!' . $this->_group . '_)[^_]*+_~siS';
        }
        else $pattern_re = '~^cache_\d++_[^_]*+_~siS';

        $deleted = 0;
        #glob() использовать нельзя, т.к. он не явл. итератором и возвращает список файлов сразу за один вызов,
        #но файлов в директории м.б. ОЧЕНЬ МНОГО, поэтому мы будем получать файлы по одному и экономить память
        if (is_dir($this->_dir) && $h = @opendir($this->_dir))
        {
            while (false !== ($file = readdir($h)))
            {
                #удаляем устаревшие файлы в течение некоторого времени, слишком долгое ожидание раздражает клиента
                if ($is_check_ttl && microtime(true) > $exec_time_max) break;

                if (! preg_match($pattern_re, $file)) continue;

                if ( ! $is_check_ttl or
                     #истекло время жизни файла?
                     #файла может уже не быть в файловой системе (удалён другим процессом)
                     (($mtime = @filemtime($this->_dir . '/' . $file)) !== false && time() > $mtime + $this->_ttl)
                   ) if (@unlink($this->_dir . '/' . $file)) $deleted++;
            }#while
            closedir($h);
        }
        return $deleted;
    }
}

?>