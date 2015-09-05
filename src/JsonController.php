<?php

namespace tourze\Controller;

use tourze\Base\Base;

/**
 * JSON控制器
 *
 * @property string contentType
 * @package tourze\Controller
 */
abstract class JsonController extends WebController
{

    /**
     * @var string
     */
    protected $_contentType = 'application/json';

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->_contentType;
    }

    /**
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->_contentType = $contentType;
    }

    /**
     * @inheritdoc
     */
    public function executeAction()
    {
        // 继续执行
        parent::executeAction();

        Base::getLog()->debug(__METHOD__ . ' render json content type', [
            'type' => $this->contentType,
        ]);
        $this->response->headers('content-type', $this->contentType);
        $this->response->body = json_encode($this->actionResult);
    }
}
