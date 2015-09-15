<?php

namespace tourze\Controller;

use tourze\Base\Base;
use tourze\Base\Component\Http;

/**
 * REST控制器
 *
 * @package tourze\Controller
 */
abstract class RestController extends WebController
{

    /**
     * @var array HTTP方法映射到控制器方法
     */
    public $methodMapping = [
        Http::GET    => 'Get',
        Http::POST   => 'Post',
        Http::PUT    => 'Put',
        Http::DELETE => 'Delete',
    ];

    /**
     * {@inheritdoc}
     */
    public function prepareActionList()
    {
        $actions = parent::prepareActionList();

        Base::getLog()->debug(__METHOD__ . ' handle rest controller request', [
            'method' => $this->request->method,
        ]);

        if (isset($this->methodMapping[$this->request->method]))
        {
            $action = $this->methodMapping[$this->request->method];
            Base::getLog()->debug(__METHOD__ . ' found rest action', [
                'method' => $this->request->method,
                'action' => $action,
            ]);
            $actions[] = $action;
        }

        Base::getLog()->debug(__METHOD__ . ' generate final rest action list', [
            'actions' => $actions,
        ]);
        return $actions;
    }

    /**
     * GET请求，获取请求
     *
     * @return mixed
     */
    abstract public function actionGet();

    /**
     * POST请求，更新请求
     *
     * @return mixed
     */
    abstract public function actionPost();

    /**
     * PUT请求，创建请求
     *
     * @return mixed
     */
    abstract public function actionPut();

    /**
     * DELETE请求，删除请求
     *
     * @return mixed
     */
    abstract public function actionDelete();

}
