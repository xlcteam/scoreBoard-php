<?php

/**
 * Dashboard presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class ResultsPresenter extends SecuredPresenter
{

	public function renderDefault()
	{
	}

    public function renderLive()
    {
    }

    public function handleUpdate()
    {
    
    }

    public function createComponentEventList()
    {
            return new EventList($this->getService('model'));
    }
    
    public function createComponentGroupList()
    {
            return new GroupList($this->getService('model'));
    }
        
}
