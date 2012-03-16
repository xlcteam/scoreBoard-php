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
                $model = $this->getService('model');

                $active_events = $model->getEvents()->where('finished', false);
                $active_groups = $model->getGroups()->where('eventID', $active_events);

                $matches = $model->getMatches();
                $playing_matches = $matches->where(
                        array('groupID' => $active_groups,
                                'state' => 'playing'));

                $this->template->matches = $playing_matches;
                $this->template->names = $model->getTeams();





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
