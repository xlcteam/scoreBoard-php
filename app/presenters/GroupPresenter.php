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
        
        public function renderNew($id = 0)
        {
        }

        public function renderList($id = 0)
        {
                if ($id === 0)
                        return $this->renderDefault();

                $this->groups = $this->getService('model')->getGroups();

                $row = $this->groups->get($id);
                if(!$row) {
                        throw new NBadRequestException('Group not found');
                }
 
                $this->template->group = $row;

        }

	protected function createComponentGroupForm($id = 0)
	{
                $this->events = $this->getService('model')->getEvents();

		$form = new NAppForm;
		$form->addText('name', 'Name of the group:')
			->setRequired('Please provide a name.');
                
                $events = $this->events->fetchPairs('id', 'name');

                $form->addSelect('eventID', 'Group in event', $events)
                        ->setDefaultValue((int) $this->getParam('id'));

		$form->addSubmit('send', 'Create');

		$form->onSuccess[] = callback($this, 'groupFormSubmitted');
		return $form;
	}
        
        public function groupFormSubmitted($form)
        {
                $this->groups = $this->getService('model')->getGroups();

                if ($form['send']->isSubmittedBy()) {
                        $row = (int) $this->getParam('id');
                        $action = $this->getParam('action');
                        $values = $form->getValues();

                        if($action == 'edit') {
                                $this->groups->find($row)->update($values);
                                $this->flashMessage("Group '{$values->name}' saved.");
                        } else {
                                $this->groups->find($row)->insert($values);
                                $this->flashMessage("Group '{$values->name}' created.");
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

        public function createComponentTeamList()
        {
                return new TeamList($this->getService('model'));
        }


}
