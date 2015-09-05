<?php

namespace tourze\Controller;

use tourze\Base\Base;
use tourze\Controller\Exception\JsonpInvalidParameterException;

/**
 * JSONP控制器
 *
 * @property string callbackParam
 * @property bool   autoSink
 * @package tourze\Controller
 */
abstract class JsonpController extends JsonController
{

    /**
     * @var string callback字符串
     */
    protected $_callbackParam = 'callback';

    /**
     * @return string
     */
    public function getCallbackParam()
    {
        return $this->_callbackParam;
    }

    /**
     * @param string $callbackParam
     */
    public function setCallbackParam($callbackParam)
    {
        $this->_callbackParam = $callbackParam;
    }

    /**
     * @var bool 自动降级标记，当没有callback时，自动调整为json方式
     */
    protected $_autoSink = false;

    /**
     * @return boolean
     */
    public function isAutoSink()
    {
        return $this->_autoSink;
    }

    /**
     * @param boolean $autoSink
     */
    public function setAutoSink($autoSink)
    {
        $this->_autoSink = $autoSink;
    }

    /**
     * @inheritdoc
     */
    public function executeAction()
    {
        if ( ! ($callback = $this->request->query($this->callbackParam)) && ! $this->autoSink)
        {
            throw new JsonpInvalidParameterException('The required parameter ":param" not found.', [
                ':param' => $this->callbackParam
            ]);
        }

        // 继续执行
        parent::executeAction();

        // 附加上callback
        if ($callback)
        {
            Base::getLog()->debug(__METHOD__ . ' append callback string', [
                'callback' => $callback,
            ]);
            $this->response->body = $callback . '(' . $this->response->body . ')';
        }
    }
}
