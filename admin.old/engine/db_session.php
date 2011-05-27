<?php

//    require_once('class.Persistent.php');
if(defined('IS_ADMIN'))
	define('SESSION_TAB','session_admin');
else
	define('SESSION_TAB','session');

    class Session 
    {
        /**
         * @var string
         */
        var $id,

        /**
         * @var int
         */
         $expires,

        /**
         * @var string
         */
         $session_data,

        /**
         * @var bool
         */
         $m_new;

        function Session($data='')
        {
        	$this->database=DATABASE();
        	if (is_array($data)){
        		$this->id=ppi($data['id']);
        		$this->expires=ppi($data['expires']);
        		$this->session_data=$data['session_data'];
        	}	
        }
        
        function delete($where){
        	$database=DATABASE();
        	$database->query('delete from '.SESSION_TAB.' where '.$where);
        }
        
        function save($mode) { // 1- insert 2 - update
        	if ($mode==1){
        		$this->database->select(
        		'INSERT INTO '.SESSION_TAB.' (id,expires,session_data) VALUES(?,?,?);',
		   			$this->id,$this->expires,$this->session_data);
        	} else {
        		$this->database->select(
        		'update '.SESSION_TAB.' set expires=?,session_data=? where id=?;',
		   			$this->expires,$this->session_data,$this->id);
           	}
        }
        
        function load($tab,$sid,$cnt){
            static $self = null;

            if (true == is_null($self)) {
                $self = new Session();
            }

            $res=@$self->database->selectRow('delete from '.SESSION_TAB.' where `expires`<?d',time());
        	if(is_null($res)){
        		$res=@$self->database->select('CREATE TABLE `'.SESSION_TAB.'` (
    `id` CHARACTER(32) BINARY NOT NULL PRIMARY KEY, /* Session ID */
    `expires` INTEGER NOT NULL, /* Время истекания сессии */
    `session_data` TEXT NOT NULL, /* Данные, хранящиеся в сессии */
    KEY(`expires`)
)');
        	}
            
        	$session_data=$self->database->selectRow('select * from '.SESSION_TAB.' where `id`=?',$sid);
        	if(!isset($session_data['session_data'])){
        		$self=null;
        	} else {
        		$self->id=$sid;
        		$self->session_data=$session_data['session_data'];
        	}
        	return $self;
        }
    }

    class SessionManager
    {
        /**
         * @var int
         */
        var $life_time,

        /**
         * @var Session
         */
         $session;

        /**
         * @return SessionManager
         */
        function& instance($reinit = false)
        {
            static $self = null;

            if (true == is_null($self)) {
                $self = new SessionManager();
                $reinit = true;
            }

            if (true == $reinit) {
                session_set_save_handler(
                    array(&$self, "open"),
                    array(&$self, "close"),
                    array(&$self, "read"),
                    array(&$self, "write"),
                    array(&$self, "destroy"),
                    array(&$self, "gc")
                );

                register_shutdown_function('session_write_close');
            }

            return $self;
        }

        function open($save_path, $sess_name)
        {
            $this->life_time = intval(get_cfg_var('session.gc_maxlifetime'));
            $this->session = new Session(
                array(
                    'id'           => (true == isset($_COOKIE[$sess_name])) ? $_COOKIE[$sess_name] : session_id(),
                    'expires'      => time() + $this->life_time,
                    'session_data' => '',
                )
            );

            $this->session->m_new = true;
            return true;
        }

        function close()
        {
            return true;
        }

        function read($sid)
        {
            $this->session = Session::load('Session', $sid, 3600);
            if (false == is_a($this->session,'Session')) {
                $this->session = new Session(
                    array(
                        'id'           => (true == isset($_COOKIE[session_name()])) ? $_COOKIE[session_name()] : session_id(),
                        'expires'      => time() + $this->life_time,
                        'session_data' => '',
                    )
                );

                $this->session->m_new = true;
            }
            else {
                $this->session->m_new = false;
            }

            return (string)$this->session->session_data; //Явное приведение типа позволит избежать трудноуловимых ошибок
        }

        function write($sid, $data)
        {
            $this->session->m_new        |= ($sid != $this->session->id);
            $this->session->id            = $sid;
            $this->session->session_data  = $data;
            $this->session->expires       = time() + $this->life_time;

            $mode = (true == $this->session->m_new) ? 1 : 2/*SAVE_UPDATE*/;
            $this->session->save($mode);

            return true;
        }

        function destroy($sid)
        {
            unset($_COOKIE[$sid]);
            Session::delete('`id`='.$sid);
            return true;
        }

        function gc($max_time)
        {
            Session::delete("`expires` < '" . time() . "'");
            return true;
        }

     }
?>