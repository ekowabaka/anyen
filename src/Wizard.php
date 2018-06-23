<?php

namespace anyen;

class Wizard
{
    /**
     * Status of a wizard page execution which requires the current page to be repeated
     */
    const REPEAT = 'repeat';

    /**
     * Status of a wizard page execution which ensures that the next page is loaded
     */
    const CONTINUE = 'continue';

    /**
     * Status of a wizard page execution which terminates the execution of the wizard
     */
    const TERMINATE = 'end';

    /**
     * The data that has been collected by the wizard so far.
     * Every page that is executed returns an associative array which may represent user input or any other data
     * generated during the execution of the page. All arrays returned are merged into this single variable.
     *
     * @var array
     */
    private $data;

    /**
     * The status set by the last wizard page.
     * @var string
     */
    private $status;

    /**
     * A user supplied callback object.
     * This object is passed around during wizard execution. It is intended to be used by the wizards scripts for
     * their own operations.
     *
     * @var mixed
     */
    private $callback;

    /**
     * Wizard constructor.
     * @param $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * Set the status of the current wizard page
     * @param $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get the status of the current wizard page
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set a value to the wizards internal data.
     * @param $key
     * @param $value
     */
    public function setValue($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Get a value from the wizard's internal data.
     * @param $key
     * @return mixed
     */
    public function getValue($key)
    {
        return $this->data[$key];
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Called by the user's wizard functions to set the status of the wizard
     * so it repeats the current page.
     */
    protected function repeatPage()
    {
        $this->status = self::REPEAT;
    }

    /**
     * Called by the user's wizard functions to set the status of the wizard
     * so the entire wizard is terminated.
     */
    protected function terminate()
    {
        $this->status = self::TERMINATE;
    }

    protected function proceed()
    {
        $this->status = self::CONTINUE;
    }

    public function showMessage($message, $type = 'info')
    {
        $_SESSION['message'] = $message;
    }
}
