<?php

namespace AdamQuaile\Behat\PhpServerExtension;

use Behat\Testwork\EventDispatcher\Event\BeforeSuiteTeardown;
use Behat\Testwork\EventDispatcher\Event\BeforeSuiteTested;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Process\Process;

class RunPhpServerListener implements EventSubscriberInterface
{
    /**
     * @var
     */
    private $host;
    /**
     * @var
     */
    private $docroot;
    /**
     * @var
     */
    private $router;

    /**
     * @var Process
     */
    private $process;

    public function __construct($host, $docroot, $router)
    {
        $this->host = $host;
        $this->docroot = $docroot;
        $this->router = $router;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            BeforeSuiteTested::BEFORE   => 'beforeSuite',
            BeforeSuiteTeardown::AFTER  => 'afterSuite'
        );
    }

    public function beforeSuite()
    {
        $router = ('' != $this->router)
            ? escapeshellarg($this->router)
            : ''
        ;

        $this->process = new Process(
            sprintf(
                'php -S %s -t %s %s',
                escapeshellarg($this->host),
                escapeshellarg($this->docroot),
                $router
            )
        );
        $this->process->start();

        sleep(1);

        if (!$this->process->isRunning()) {
            throw new \RuntimeException('PHP built-in server process terminated immediately');
        }

        // Also trap fatal errors to make sure running processes are terminated
        register_shutdown_function([$this, 'afterSuite']);
    }

    public function afterSuite()
    {
        if ($this->process && $this->process->isRunning()) {
            $this->process->stop();
        }
    }

}
