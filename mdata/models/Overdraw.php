<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ksnk
 * Date: 03.05.11
 * Time: 19:26
 * To change this template use File | Settings | File Templates.
 */
 
class CMyUrlManager extends CUrlManager {
    const FREE_FORMAT='free';

    protected function createUrlDefault($route,$params,$ampersand)
    {
        if($this->getUrlFormat()===self::FREE_FORMAT){

        } else
            return parent::createUrlDefault($route,$params,$ampersand);
    }

    /**
     * Parses the user request.
     * @param CHttpRequest $request the request application component
     * @return string the route (controllerID/actionID) and perhaps GET parameters in path format.
     */
    public function parseUrl($request)
    {
        if($this->getUrlFormat()===self::FREE_FORMAT){

        } else
            return parent::parseUrl($request);
    }
    
    /**
     * Sets the URL format.
     * @param string $value the URL format. It must be either 'path' or 'get'.
     */
    public function setUrlFormat($value)
    {
        if($value===self::FREE_FORMAT)
            $this->_urlFormat=$value;
        else
            parent::setUrlFormat($value);
    }

}