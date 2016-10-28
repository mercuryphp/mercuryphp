<?php

namespace System\Web\Mvc;

use System\Core\Str;
use System\Core\Obj;
use System\Core\Convert;
use System\Diagnostics\Trace;
use System\Diagnostics\TraceListenerCollection;
use System\Diagnostics\DefaultTraceListener;
use System\Configuration\ConfigurationReader;
use System\Configuration\EmptyReader;
use System\Configuration\Configuration;
use System\Web\Routing\RouteCollection;
use System\Web\Http\HttpContext;
use System\Web\Http\HttpRequest;
use System\Web\Http\HttpResponse;
use System\Web\Http\Session\Session;
use System\Web\Http\Session\FileSessionHandler;

abstract class HttpApplication {
    
    private $traceListeners;
    private $routes;
    private $httpContext;
    private $config;


    /**
     * Initializes an instance of HttpApplication by providing the application 
     * root path.
     */
    public function __construct(string $rootPath){
        $httpRequest = new HttpRequest();
        $httpResponse = new HttpResponse();
        $session = new Session($httpRequest, $httpResponse);
        $this->traceListeners = new TraceListenerCollection(new DefaultTraceListener());
        $this->routes = new RouteCollection();
        $this->httpContext = new HttpContext($httpRequest, $httpResponse, $session);
        $this->httpContext->getRequest()->setApplicationPath($rootPath);
        $this->httpContext->getSession()->setHandler(new FileSessionHandler());
    }
    
    /**
     * Gets the route collection instance which can be used to register routes.
     */
    protected function getRoutes() : \System\Web\Routing\RouteCollection {
        return $this->routes;
    }
    
    /**
     * Gets the HttpContext instance for the application.
     */
    protected function getHttpContext() : \System\Web\Http\HttpContext {
        return $this->httpContext;
    }
    
    public final function start(){
        $configurationReader = new ConfigurationReader('configx.php');
        $this->config = $configurationReader->hasFile() ? new Configuration($configurationReader) : new Configuration(new EmptyReader());
        $this->init();
    }

    /**
     * This method is an application event and is called before all other events.
     * It can be used to register routes and initialize application settings.
     */
    public function load(){}
    
    /**
     * This method is an application event and is called after the load() event.
     * This method configures and dispatches a controller. This method cannot be
     * overriden.
     */
    public final function run(){
    	
    	if(!$this->routes->count()){
            throw new \RuntimeException('One or more routes must be registered.');
    	}
    	
    	foreach($this->routes as $route){
            if($route->getRouteHandler()->execute($route, $this->httpContext)){

                $class = Str::set($route->getControllerPathPattern())->template(
                    $this->httpContext->getRequest()->getRouteData()->toArray(),
                    ['module' => 'lc.ucf', 'controller' => 'lc.ucf']
                )->trim('.');

                try{
                    $controller = Obj::getInstance((string)$class);
                    $controller->getRegistry()->merge(Obj::getProperties($this, \ReflectionProperty::IS_PUBLIC |  \ReflectionProperty::IS_PROTECTED));
                }catch(\ReflectionException $e){
                    throw new ControllerNotFoundException($this->httpContext, $class);
                }
                
                if(!$controller instanceof Controller){
                    throw new HttpException(sprintf("The controller '%s' does not inherit from System.Web.Mvc.Controller.", $class));
                }
                
                Trace::write('Application beforeAction()');
                $this->beforeAction($controller);
                
                Trace::write('Controller execute()');
                $controller->execute($this->httpContext);

                Trace::write('Application afterAction()');
                $this->afterAction($controller);
                
                break;
            }
    	}
    }
    
    /**
     * This method is an application event and is called before the controllers
     * load() and action() methods. You can use this method to configure the 
     * controller at an application level.
     */
    public function beforeAction(Controller $controller){ }
    
    /**
     * This method is an application event and is called after the controllers
     * render() method.
     */
    public function afterAction(Controller $controller){ }
    
    /**
     * This method is an application event and is called at the end of the 
     * applications cycle. It is used to flush output to the browser.
     */
    public final function end(){
        $this->httpContext->getSession()->save();
        $this->httpContext->getResponse()->flush();

        foreach($this->traceListeners as $listener){
            $listener->setData(Trace::getData());
            $listener->write();
        }
    }

    public function error(\Exception $e){}
    
    private function init(){
        
        if($this->config->hasPath('session.name')){
            $this->getHttpContext()->getSession()->setName($this->config->get('session.name'));
        }
        
        if($this->config->hasPath('session.http')){
            $this->getHttpContext()->getSession()->isHttpOnly(Convert::toBoolean($this->config->get('session.http')));
        }
        
        if($this->config->hasPath('session.secure')){
            $this->getHttpContext()->getSession()->isSecure(Convert::toBoolean($this->config->get('session.secure')));
        }

        if($this->config->hasPath('session.handler')){
            try{
                $handlerInstance = Obj::getInstance($this->config->get('session.handler'), $this->config->get('session.handlerArgs', []));
                $this->httpContext->getSession()->setHandler($handlerInstance);
            }
            catch(\ReflectionException $re){
                throw new \System\Web\Http\Session\SessionException(sprintf("The session handler '%s' does not exist.", $this->config->get('session.handler')));
            }
        } 
    }
}