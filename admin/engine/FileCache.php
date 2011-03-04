<?php

/**
 * ����� ��� ����������� ������ � �������� �������.
 *
 * ����������:
 *   ����������� ���-������� � ��������, ��� ���� ���������� ���������� � ��������� � ��������.
 *   ����� �.�. ����������� � �������� �� ������� ������������� � ���������.
 *   ��� ����������������� ������ ������ ���������� Memcache/MemcacheDB.
 *
 * ������������� �����������:
 *   * ������������� ��������� ������ �� ������� �����������
 *   * ����������� ������ ��� ������������� ������������� � ������������ ���� ����������� ����������
 *   * ����������� ��������� ���������� ������ ����
 *   * ��������� �� ���� ���������� Last-Modified, Content-Type
 *   * ������ ��� �������� ������ ���� ��� ������������, ������ ��� ����� ���� �������
 *   * �������������� �������� ���������� ������ �� ����
 *
 * ��� ��������� ������ �� ���� ��� ��������� �� ��������������� ����:
 *   * ����� ���� ���/������/�������� ��� ����� ����� ���� �������
 *   * ������-���� ���������� ����� ��� ��� ��� ����� ��� ����������� ����������
 *
 * @license  http://creativecommons.org/licenses/by-nc-sa/3.0/
 * @author   Nasibullin Rinat, http://orangetie.ru/
 * @charset  ANSI
 * @version  1.2.2
 */
class FileCache
{
     var $dependent_files = array(); #�����. ������ ��������� ������, ��� ������� ���. �����, � ���������� ����� ����������� � unixtime
     var $filename = null;           #������ ��� �����, ��������������� � ������������
     var $fp = null;                 #������ ��� fopen()

    #����� ������������:
     var $_dir = '/tmp/';      #���������� ��� �������� ������������ ������
     var $_ttl = 10800;        #����� ����� ������ � ����
     var $_cleaning_probability = 100;  #���������� ��� ��������������� �������� ���� (1 �� X)
     var $_group = '';         #������ ��������, ������
     var $_user_id = 0;        #������������� �������� ������������, ����� (���� ��� ���������� ������������)
     var $_hash = '';          #��� (���������� �������������) ��������, ������
     var $_is_enabled = true;  #����������� ��������?

    /**
     * �����������
     *
     * @param   array     $options  ������ �����  (��. ���� ������� �������� ����������)
     */
     function FileCache( $options)
    {
        $this->setOptions($options);
        $this->filename = preg_replace('~[/\\\\]+~s', '/', $this->_dir . '/cache_' . intval($this->_user_id) . '_' . $this->_group . '_' . $this->_hash);
    }
/*
     function __destruct()
    {
        #��������� �������������� �������� ���������� ������ � ������������ 1 �� X
        if ($this->_is_enabled
            && $this->_cleaning_probability > 0
            && rand(1, $this->_cleaning_probability) == 1) $this->clean($is_check_ttl = true);
    }
*/
    /**
     * ������������� �����
     *
     * @param   array     $options  ������ �����  (��. ���� ������� �������� ���������� ������)
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
     * ���� �������� ������, ��������� �����, �� ������� ����������� ������� ������� �������������� ��������.
     * ���� �������� �� ������, ���������� ������ ������.
     * ����� ������ ����������� � ������� ������.
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
                  #����� ����� ��� �� ���� � �������� �������, ������� ������ @
                  && ($mtime = @filemtime($file)) !== false
               )  $this->dependent_files[$file] = pack('V', $mtime);
        }#foreach
        return true;
    }

    #������� ��� ��� ���������� ����� �� ����
    #���������� ���-�� �������� ������
     function clean($is_check_ttl = false)
    {
        #��������, ������� ���� ���������� -- ������ �� ��������
        return $this->_clean($this->_user_id, '*', $is_check_ttl) +   #������� ��� ������������
               $this->_clean('*', $this->_group, $is_check_ttl)   +   #����� ��� ������
               $this->_clean('*', '*', $is_check_ttl);                #� ������ ����� �� ���������
    }

    #������� ��� ��� ���������� ����� �� ���� ��� �������� ������������
    #���������� ���-�� �������� ������
     function cleanForUser($is_check_ttl = false)
    {
        return $this->_clean($this->_user_id, '*', $is_check_ttl);
    }

    #������� ��� ��� ���������� ����� �� ���� ��� ������� ������
    #���������� ���-�� �������� ������
     function cleanForGroup($is_check_ttl = false)
    {
        return $this->_clean('*', $this->_group, $is_check_ttl);
    }

    /**
     * ���������� ���������� ��������������� ����� ��� FALSE,
     * ���� ����������� ���������, ���� �� ������/������/�����, ��� �������.
     *
     * @param   mixed(int/null)        &$last_modified       last modification time in unixtime
     * @param   mixed(string/null)     &$content_type
     * @param   bool                   $is_check_ttl        ��������� ������������ ����?
     * @param   bool                   $is_check_depends    ��������� ������������ ��������� ������?
     * @return  mixed(string/false)
     */
     function read(&$last_modified , &$content_type , $is_check_ttl = true, $is_check_depends = true)
    {
        if (! $this->_is_enabled) return false;

        #������� ������� ������ ����, ���� ��� ��� ���; ���� �� ���� ����������, ��� ��� �� ��������
        if ($fp = @fopen($this->filename, 'a+b')) fclose($fp);
        else return false;

        #��������� ����
        $this->fp = @fopen($this->filename, 'r+b');
        if (! $this->fp) return false;  #��� ���� ��c���� ��� ���� ����� � ������ ��������

        $data = $this->_read($fstat);
        $content = $this->_content($data, $fstat, $last_modified, $content_type, $is_check_ttl, $is_check_depends);
        if ($content !== false) return $content;

        #� ���� ����� �� ��������, ��� ���� ��� � ���������� ���-�������� ����� ������������,
        #������� ������ ������������ ���������� LOCK_EX (������ � ���� ������������ ����� ������ ���� �������)
        $flock_waiting_sec = 2;
        $flock_ping_sec    = 0.1;
        $flock_iterations  = $flock_waiting_sec / $flock_ping_sec;
        $flock_counter     = 0;
        $is_lock_ex        = false;

        #���, ���� �� �� ������ ������������� � ������� ���������� ������, ������� ������ �������� ���������� �������
        while ($flock_counter < $flock_iterations)
        {
            if (flock($this->fp, LOCK_EX + LOCK_NB)) #����� ���������� ����������, ��������� LOCK_NB
            {
                $is_lock_ex = true;
                break;
            }
            else usleep($flock_ping_sec * 1000000); #������ �� ������, ����� ����������� ������ ��������� �������� ��������� ������
            $flock_counter++;
        }#while

        #���� ������������� ���� �� �������, �������� ���������� ������ ������ �� ����
        if (! $is_lock_ex) return $this->_content($data, $fstat, $last_modified, $content_type, $is_check_ttl = false, $is_check_depends = false);
        #���� ������� ������������� ���� � ������ �������, �� ������ ������� � ���� �� �����
        if ($flock_counter === 0) return false;
        #����� ������� ��� ��� �������� ������ ����, ��������� ������ ���������
        $data = $this->_read($fstat, $is_lock_ex);
        return $this->_content($data, $fstat, $last_modified, $content_type, $is_check_ttl, $is_check_depends);
    }

     function _read(&$fstat, $is_lock_ex = false)
    {
        #���������� ����������� ���������� LOCK_SH ��� ������ ���� (������ �� ����� ������������ ����� ����� ���������)
        #����� ����� ��������� ������������ ���������� � ������ ���������, ������� ����� � ����
        if (! flock($this->fp, LOCK_SH)) return false;
        if ($is_lock_ex) fseek($this->fp, 0, SEEK_SET);
        $fstat = fstat($this->fp);
        if (! $fstat['size']) return false;
        return fread($this->fp, $fstat['size']);
    }

     function _content($data, $fstat, &$last_modified , &$content_type , $is_check_ttl = true, $is_check_depends = true)
    {
        if ($data === false || strlen($data) === 0) return false; #���� ������
        if ($is_check_ttl && time() > $fstat['mtime'] + $this->_ttl) return false;  #������� ����� ������������ �����

        $data = @unserialize($data);
        if (! is_array($data)) return false;    #����� ��������������� ������

        list($content_type, $gz_dependent_files, $content) = $data;
        unset($data);

        #��������� ������������ ��������� ������
        if ($is_check_depends)
        {
            #������������� � ������������� ������ ��������� ������:
            $dependent_files = @gzuncompress($gz_dependent_files);
            if ($dependent_files === false) return false;  #����� ������������� ������
            $dependent_files = @unserialize($dependent_files);
            if (! is_array($dependent_files)) return false; #����� ��������������� ������

            foreach ($dependent_files as $file => $Vmtime)
            {
                #����� ����� ��� �� ���� � �������� �������, ������� ������ @
                if (($mtime = @filemtime($file)) === false) return false; #���� �� ����������
                if (pack('V', $mtime) !== $Vmtime) return false;          #���� ��� �������������
            }#foreach
            $this->dependent_files = $dependent_files;
        }
        $last_modified = $fstat['mtime'];
        return $content;
    }

    /**
     * ���������� ������������ ������ � ����.
     * ���������� ���-�� ���������� ���� ��� FALSE, ���� ����������� ���������
     *
     * @param   string    $s
     * @param   string    $content_type       ��������: 'text/html', 'text/xml', ...
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
     * ������� ���������� ��� ��� ����� �� ����.
     * ��� $is_check_ttl = true ����� ��������� � ������������ �� ������� ������, ����� �� ���������� ������� ����� �����
     *
     * @param   mixed(null/string)  $group         ���� ������ �������, �� ����� ������� ����� ������ ��� ���� ������
     * @param   bool                $is_check_ttl  ��������� ����� ����� ������?
     * @return  int                                ���������� ���-�� ��������� ������
     */
     function _clean($user_id = '*', $group = '*', $is_check_ttl = false)
    {
        #if (! is_scalar($user_id) && $user_id !== null) trigger_error('A scalar/null type expected in first parameter, '  . gettype($user_id) . ' type given!', E_USER_ERROR);
        #if (! is_string($group))   trigger_error('A string type expected in second parameter, ' . gettype($group)   . ' type given!', E_USER_ERROR);

        static $exec_time_max = null;  #����� � unixtime � ��������������, �� �������� ������ �������� ����� �����������, ��� $is_check_ttl = true

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
        #glob() ������������ ������, �.�. �� �� ���. ���������� � ���������� ������ ������ ����� �� ���� �����,
        #�� ������ � ���������� �.�. ����� �����, ������� �� ����� �������� ����� �� ������ � ��������� ������
        if (is_dir($this->_dir) && $h = @opendir($this->_dir))
        {
            while (false !== ($file = readdir($h)))
            {
                #������� ���������� ����� � ������� ���������� �������, ������� ������ �������� ���������� �������
                if ($is_check_ttl && microtime(true) > $exec_time_max) break;

                if (! preg_match($pattern_re, $file)) continue;

                if ( ! $is_check_ttl or
                     #������� ����� ����� �����?
                     #����� ����� ��� �� ���� � �������� ������� (����� ������ ���������)
                     (($mtime = @filemtime($this->_dir . '/' . $file)) !== false && time() > $mtime + $this->_ttl)
                   ) if (@unlink($this->_dir . '/' . $file)) $deleted++;
            }#while
            closedir($h);
        }
        return $deleted;
    }
}

?>