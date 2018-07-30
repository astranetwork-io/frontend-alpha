<?php
namespace Astra\SharedBundle\Services;

class SharedVariableService
{
    const NAME_CURRENT_PROJECT = '__current_project__';
    const NAME_CURRENT_PROJECT_TASK = '__current_project_task__';
    const NAME_CURRENT_TASK = '__current_task__';
    const NAME_PRIVATE_MESSAGE = '__private_message__';
    const NAME_CURRENT_USER_ROLE = '__user_rolle__';

    protected $variables = [];

    function set($name, $value)
    {
        if(empty($name)) throw new \Exception('Wrong "name" param');
        $this->variables[$name] = $value;
        return $value;
    }

    function get($name, $default = null)
    {
        if (!isset($this->variables[$name])) return $default;
        return $this->variables[$name];
    }

    function remove($name)
    {
        if (!isset($this->variables[$name])) return null;
        $value = $this->variables[$name];
        unset($this->variables[$name]);
        return $value;
    }

}