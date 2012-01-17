<?php

/**
 * Event presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class EventPresenter extends SecuredPresenter
{
        private $events;

	public function renderDefault()
	{

	}

        public function renderList($id = 0)
        {
                if ($id === 0)
                        return $this->renderDefault();

                $this->events = $this->getService('model')->getEvents();

                $row = $this->events->get($id);
                if(!$row) {
                        throw new NBadRequestException('Event not found');
                }
 
                $this->template->event = $row;

        }

        public function createComponentGroupList()
        {
                return new GroupList($this->getService('model'));
        }

	protected function createComponentEventForm()
	{
		$form = new NAppForm;
		$form->addText('name', 'Name of the event:')
			->setRequired('Please provide a name.');

		$form->addCheckbox('finished', 'Finished');


		$form->addSubmit('send', 'Create');

		$form->onSuccess[] = callback($this, 'eventFormSubmitted');
		return $form;
	}

        public function eventFormSubmitted($form)
        {
                $this->events = $this->getService('model')->getEvents();

                if ($form['send']->isSubmittedBy()) {
                        $row = (int) $this->getParam('id');
                        $values = $form->getValues();

                        if($row > 0) {
                                $this->events->find($row)->update($values);
                        } else {
                                $values->userID = $this->getUser()->getIdentity()->id;
                                $values->finished = 0;
                                $this->events->find($row)->insert($values);
                        }
                        $this->redirect('Dashboard:');
                }
                
        }

        public function renderEdit($id = 0)
        {
                $this->events = $this->getService('model')->getEvents();
                
                $form = $this['eventForm'];
                $form['send']->caption = 'Save';
                if(!$form->isSubmitted()) {
                        $row = $this->events->get($id);
                        if(!$row) {
                                throw new NBadRequestException('Event not found');
                        }
                        $form->setDefaults($row);
                }

        }

}
