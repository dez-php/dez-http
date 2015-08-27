<?php

    namespace Dez\Http\Request;

    interface RequestInterface {

        public function get();

        public function getPost();

        public function isPost();

        public function isAjax();

    }