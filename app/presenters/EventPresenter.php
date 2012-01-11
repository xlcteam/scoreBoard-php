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

	protected function createComponentEventForm()
	{
		$form = new NAppForm;
		$form->addText('name', 'Name of the event:')
			->setRequired('Please provide a name.');

		$form->addSubmit('send', 'Create');

		$form->onSuccess[] = callback($this, 'eventFormSubmitted');
		return $form;
	}

        public function eventFormSubmitted($form)
        {
                $values = $form->getValues();
                $values->uID = $this->getUser()->getIdentity()->id;
                $values->finished = 0;
                $this->getService('model')->getEvents()->insert($values);
                $this->redirect('Dashboard:');
                
        }

        public function renderEdit($id = 0)
        {
                $this->events = $this->getService('model')->getEvents();
                
                $form = $this['eventForm'];
                if(!$form->isSubmitted()) {
                        $row = $this->events->get($id);
                        if(!$row) {
                                throw new NBadRequestException('Event not found');
                        }
                        $form->setDefaults($row);
                }

        }


}
