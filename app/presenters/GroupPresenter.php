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
                $this->template->id = $id;
        }

	protected function createComponentGroupForm($id = 0)
	{
                $this->events = $this->getService('model')->getEvents();

		$form = new NAppForm;
		$form->addText('name', 'Name of the group:')
			->setRequired('Please provide a name.');
                
                $events = $this->events->fetchPairs('id', 'name');

                $form->addSelect('event', 'Group for event', $events)
                        ->setDefaultValue($this->template->id);

		$form->addSubmit('send', 'Create');

		$form->onSuccess[] = callback($this, 'eventGroupSubmitted');
		return $form;
	}
        
        public function eventFormSubmitted($form)
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

}
