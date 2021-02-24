<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WP_Locator_Plugin_Loader {

    protected $filters = [];

    protected $actions = [];

    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1)
    {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1)
    {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    public function run()
    {
        // Register Actions
        foreach ($this->actions as $action){
            add_action($action['hook'], [$action['component'], $action['callback']], $action['priority'], $action['accepted_args']);
        }

        // Register Filters
        foreach ($this->filters as $filter){
            add_filter($filter['hook'], [$filter['component'], $filter['callback']], $filter['priority'], $filter['accepted_args']);
        }
    }

    protected function add($hooks, $hook, $component, $callback, $priority, $accepted_args)
    {
        $hooks[] = [
            'hook' => $hook,
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args
        ];

        return $hooks;
    }

}