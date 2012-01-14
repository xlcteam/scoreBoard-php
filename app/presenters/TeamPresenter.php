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
                $this->template->id = $id;
        }

	protected function createComponentTeamForm()
	{

                $events = $this->getService('model')->getEvents();
                $groups = array();
                foreach($events as $event){
                        $g = $this->getService('model')->getGroups()->where('eventID', $event->id);
                        foreach ($g as $group) {
                                $groups[$group->id] = $group->name . " - " . $event->name;
                        }
                }

		$form = new NAppForm;
		$form->addText('name', 'Name of the team:')
			->setRequired('Please provide a name.');
                

                $select = $form->addSelect('groupID', 'Group:', $groups)
                        ->setDefaultValue($this->template->id);

		$form->addSubmit('send', 'Create');

		$form->onSuccess[] = callback($this, 'eventGroupSubmitted');
		return $form;
	}
        
        public function eventFormSubmitted($form)
        {
                $this->teams = $this->getService('model')->getTeams();

                if ($form['send']->isSubmittedBy()) {
                        $row = (int) $this->getParam('id');
                        $values = $form->getValues();

                        if($row > 0) {
                                $this->teams->find($row)->update($values);
                        } else {
                                $this->teams->find($row)->insert($values);
                        }
                        $this->redirect('Dashboard:');
                }
                
        }

}
