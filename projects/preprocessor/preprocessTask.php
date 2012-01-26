<?php
/**
 * preprocessor Phing task
 *
 * <%=point('hat','comment');%>
 */
/**
 * just a comment
 */
$dir=dirname(__FILE__);
include_once ($dir.DIRECTORY_SEPARATOR."preprocessor.class.php");
include_once ($dir.DIRECTORY_SEPARATOR."point.ext.php");

 //   date_default_timezone_set('Europe/Moscow');

require_once 'phing/Task.php';
    /**
     *
     * prepare set of file for deploying by execute them with php.
     *
     * <code>
     * <preprocess config="${config}">
     *      <property file="svn.prop"/>
     *      <property name="target" value="${target}"/>
     *      <property name="dst" value="${dst}"/>
     * </preprocessor>
     * </code>
     * @subpackage  phing
     */
    class PreprocessTask extends Task
    {
        /** @var preprocessor */
        private $preprocessor;

        private $config='';
        private $parameters = array();
        private $parameter;

        private $force=false;

        protected $logLevel = Project::MSG_INFO;

        /**
         * Set level of log messages generated (default = info)
         * @param string $level
         */
        public function setLevel($level)
        {
            switch ($level)
            {
                case "error": $this->logLevel = Project::MSG_ERR; break;
                case "warning": $this->logLevel = Project::MSG_WARN; break;
                case "info": $this->logLevel = Project::MSG_INFO; break;
                case "verbose": $this->logLevel = Project::MSG_VERBOSE; break;
                case "debug": $this->logLevel = Project::MSG_DEBUG; break;
            }
        }

        public function __construct() {
            $this->filesets = array();
            $this->completeDirMap = array();
        }

        public function setConfig($config) {
            $this->config = $config;
        }

        public function setForce($force) {
            $this->force = $force;
        }

        public function createParam() {
            $this->parameter = new Param();
            $this->parameters[] = $this->parameter;
            return $this->parameter;
        }

        public function init() {
            $this->preprocessor=new preprocessor();
        }

        public function main() {
            $this->log('<%=$version%>', Project::MSG_INFO);
            // define a force tag
            $time=false;
            if($this->force){
                $xtime=strtotime($this->force);
                if(!$xtime){
                    $time=time();
                    if ($this->force!='force')
                        $this->log('wrong date "'.$this->force.'"' , Project::MSG_WARN);
                } else {
                    $time=$xtime;
                }
            }

            $this->preprocessor->cfg_time($time);
            $this->preprocessor->logLevel=$this->logLevel;
            // difine variable definitions
            foreach($this->parameters as $v){
                $file=$v->getFile();
                if(is_readable($file)){
                    foreach(file($file) as $vv){
                        if(preg_match('/^(?:\;.*|\#.*|([^=]+)=(.*))$/',$vv,$mm)){
                            if(!empty($mm[1])){
                                $this->preprocessor->export(trim($mm[1]),trim($mm[2]));
                            }
                        }
                    }
                } else {
                    $this->preprocessor->export($v->getName(),$v->getValue());
                }
            }
            //run it!
            if(!!$this->config){
                $this->log('making "'.$this->config.'"', Project::MSG_WARN);
                $GLOBALS['preprocessor']=&$this->preprocessor;
                $this->preprocessor->xml_read($this->config);
                $this->preprocessor->process();
            }
         }

    }

/**
 * additional class to cover file parameter
 * @subpackage  phing
 */
class Param extends Parameter {

    private $file=array();

    function setFile($file) {
        $this->file=$file;
    }

    function getFile() {
        return $this->file;
    }

}