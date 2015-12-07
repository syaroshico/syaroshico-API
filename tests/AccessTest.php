<?php

class AccessTest extends TestCase
{
    /**
     * Can I request index?
     *
     * @return void
     */
    public function testReqIndex()
    {
        $this->visit('/')
             ->assertResponseOk();
    }

    public function testApiCall() {
        $this->visit('/api/v1/count.json')->isJson();
    }
}
