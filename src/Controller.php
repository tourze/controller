<?php

namespace tourze\Controller;

use tourze\Base\Base;
use tourze\Base\Object;
use tourze\Controller\Exception\ActionMissingException;
use tourze\Http\Exception\Http404Exception;
use tourze\Http\Exception\HttpException;
use tourze\Http\Response;
use tourze\Http\Request;

/**
 * 控制器基础类，请求流程大概为：
 *
 *     $controller = new FooController($request);
 *     $controller->before();
 *     $controller->actionBar();
 *     $controller->after();
 *
 * @property Request  request
 * @property Response response
 * @property mixed    actionResult
 * @property bool     break
 * @package tourze\Controller
 */
abstract class Controller extends Object
{

    /**
     * @var  Request  创建控制器实例的请求
     */
    public $_request;

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->_request = $request;
    }

    /**
     * @var  Response  用于返回控制器执行结果的响应实例
     */
    public $_response;

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->_response;
    }

    /**
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->_response = $response;
    }

    /**
     * @var mixed action的执行结果
     */
    public $_actionResult;

    /**
     * @return mixed
     */
    public function getActionResult()
    {
        return $this->_actionResult;
    }

    /**
     * @param mixed $actionResult
     */
    public function setActionResult($actionResult)
    {
        $this->_actionResult = $actionResult;
    }

    /**
     * @var boolean 标志位，是否停止执行
     */
    protected $_break = false;

    /**
     * @return bool
     */
    public function isBreak()
    {
        return $this->_break;
    }

    /**
     * @param bool $break
     */
    public function setBreak($break)
    {
        $this->_break = $break;
    }

    /**
     * 开始处理请求
     *
     * @throws HttpException
     * @throws Http404Exception
     * @return Response
     */
    public function execute()
    {
        Base::getLog()->debug(__METHOD__ . ' controller execute - start');

        if ( ! $this->break)
        {
            $this->executeBefore();
        }
        if ( ! $this->break)
        {
            $this->executeAction();
        }
        if ( ! $this->break)
        {
            $this->executeAfter();
        }

        Base::getLog()->debug(__METHOD__ . ' controller execute - end');
        return $this->response;
    }

    /**
     * 准备好要遍历的动作列表
     *
     * @return array
     */
    public function prepareActionList()
    {
        Base::getLog()->debug(__METHOD__ . ' prepare action list for mapping - start');
        $actionSign = '';
        foreach (explode('-', $this->request->action) as $part)
        {
            $actionSign .= ucfirst($part);
        }

        $actions = [
            'action' . $actionSign,
        ];

        Base::getLog()->debug(__METHOD__ . ' prepare action list for mapping - end', [
            'actions' => $actions,
        ]);
        return $actions;
    }

    /**
     * 执行action
     *
     * @throws HttpException
     */
    public function executeAction()
    {
        Base::getLog()->debug(__METHOD__ . ' execute requested action');

        $actions = $this->prepareActionList();

        $matchAction = false;
        foreach ($actions as $action)
        {
            if (method_exists($this, $action))
            {
                $matchAction = $action;
                Base::getLog()->debug(__METHOD__ . ' find matched action', [
                    'action' => $matchAction,
                ]);
                break;
            }
        }

        // 检查对应的方法是否存在
        if ( ! $matchAction)
        {
            $this->missingAction();
        }

        // 保存结果
        Base::getLog()->debug(__METHOD__ . ' run action - start');
        $this->actionResult = $this->{$matchAction}();
        Base::getLog()->debug(__METHOD__ . ' run action - end');
    }

    /**
     * 动作不存在时的操作
     *
     * @throws \tourze\Http\Exception\HttpException
     */
    public function missingAction()
    {
        throw new ActionMissingException('The request action not found');
    }

    /**
     * 执行action前的操作，可以做预备操作
     */
    public function before()
    {
        // empty
    }

    /**
     * 不要继承这个方法
     */
    private function executeBefore()
    {
        Base::getLog()->debug(__METHOD__ . ' execute before action - start');
        $this->before();
        Base::getLog()->debug(__METHOD__ . ' execute before action - end');
    }

    /**
     * 执行action后的操作，可以用来做收尾工作
     */
    public function after()
    {
        // empty
    }

    /**
     * 不要继承这个方法
     */
    private function executeAfter()
    {
        Base::getLog()->debug(__METHOD__ . ' execute after action - start');
        $this->after();
        Base::getLog()->debug(__METHOD__ . ' execute after action - end');
    }
}
