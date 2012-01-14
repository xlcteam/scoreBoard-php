<?php

/**
 * Group presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class GroupPresenter extends NPresenter
{
        private $groups;

        private $events;
        
        public function renderDefault()
        {
                
        }
        
        public function renderNew()
        {

        }

	protected function createComponentGroupForm($id = 0)
	{
                $this->events = $this->getService('model')->getEvents();

		$form = new NAppForm;
		$form->addText('name', 'Name of the group:')
			->setRequired('Please provide a name.');
                
                $events = $this->events->fetchPairs('id', 'name');

                $form->addSelect('event', 'Group for event', $events);

		$form->addSubmit('send', 'Create');

		$form->onSuccess[] = callback($this, 'groupFormSubmitted');
		return $form;
	}
        
        public function groupFormSubmitted($form)
        {
                $this->groups = $this->getService('model')->getGroups();

                if ($form['send']->isSubmittedBy()) {
                        $row = (int) $this->getParam('id');
                        $values = $form->getValues();

                        if($row > 0) {
                                $this->groups->find($row)->update($values);
                        } else {
                                $this->groups->find($row)->insert($values);
                        }
                        $this->redirect('Dashboard:');
                }
                
        }

        public function renderEdit($id = 0)
        {
                $this->groups = $this->getService('model')->getGroups();
                
                $form = $this['groupForm'];
                $form['send']->caption = 'Save';
                if(!$form->isSubmitted()) {
                        $row = $this->groups->get($id);
                        if(!$row) {
                                throw new NBadRequestException('Group not found');
                        }
                        $form->setDefaults($row);
                }

        }
}
