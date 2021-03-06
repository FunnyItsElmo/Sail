<?php
namespace Sail;

class Node {

    public $value;

    public $next = array();

    public $callable = array();

    public $middleware = array();

    public function __construct ($value) {
        $this->value = $value;
    }

    /**
     * Gets the next Node
     *
     * @param node $key            
     * @return Node
     */
    public function getNext ($key) {
        return isset($this->next[$key]) ? $this->next[$key] : null;
    }

    /**
     * Adds a Node to the next array with
     * the specified key as index
     *
     * @param string $key            
     * @param Node $node            
     */
    public function putNext ($key, $node) {
        $this->next[$key] = $node;
    }

    /**
     * Gets the callable for the specified key
     *
     * @param string $key            
     */
    public function getCallable ($key) {
        return isset($this->callable[$key]) ? $this->callable[$key] : null;
    }

    /**
     * Adds a callable to the callable array
     * with the specified key as index
     *
     * @param string $key            
     * @param closure $value            
     */
    public function putCallable ($key, $value) {
        $this->callable[$key] = $value;
    }

    /**
     * Replace the middleware of this and
     * child nodes
     *
     * @param array $middleware            
     */
    public function setMiddleware ($middleware) {
        $this->middleware = $middleware;
        
        foreach ($this->next as $next) {
            $next->setMiddleware($middleware);
        }
    }

    /**
     * Gets the middleware array
     *
     * @return array
     */
    public function getMiddleware () {
        return $this->middleware;
    }

    /**
     * Merges this and another node
     *
     * @param Node $node            
     */
    public function merge ($node) {
        $this->next = $this->next + $node->next;
        $this->callable = $this->callable + $node->callable;
        $this->middleware = $this->middleware + $node->middleware;
    }
}