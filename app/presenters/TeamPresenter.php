<?php

/**
 * Team presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class TeamPresenter extends NPresenter
{
        private $teams;

        private $groups;
        
        public function renderDefault()
        {
                
        }
        
        public function renderNew($id = 0)
        {
        }

        public function renderList($id = 0)
        {
                $this->template->id = $id;
        }


	protected function createComponentTeamForm()
	{

                $events = $this->getService('model')->getEvents();
                $groups = array();
                foreach($events as $event){
                        $g = $this->getService('model')->getGroups()->where('eventID', $event->id);
                        foreach ($g as $group) {
                                $groups[$group->id] = $event->name . " - " . $group->name;
                        }
                }

		$form = new NAppForm;
		$form->addText('name', 'Name of the team:')
			->setRequired('Please provide a name.');
                
                $select = $form->addSelect('groupID', 'Group:', $groups)
                        ->setDefaultValue((int) $this->getParam('id'));

		$form->addSubmit('send', 'Create');

		$form->onSuccess[] = callback($this, 'eventFormSubmitted');
		return $form;
	}
        
        public function eventFormSubmitted($form)
        {
                $this->teams = $this->getService('model')->getTeams();

                if ($form['send']->isSubmittedBy()) {
                        $row = (int) $this->getParam('id');
                        $values = $form->getValues();
                        $action = $this->getParam('action');

                        if($action == 'edit') {
                                $this->teams->find($row)->update($values);
                                $this->flashMessage("Team '{$values->name}' saved.");
                        } else {
                                $this->teams->find($row)->insert($values);
                                $this->flashMessage("Team '{$values->name}' created.");
                        }
                        

                        $this->redirect('Dashboard:');
                }
                
        }

}
