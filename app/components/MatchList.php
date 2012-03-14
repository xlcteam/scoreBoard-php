<?php


class MatchList extends NControl
{
        /** @var NTableSelection */
        public $model;

	public function __construct($model)
	{
		parent::__construct();
                $this->model = $model;

	}


	public function render($group, $unplayed = true)
	{
		$template = $this->template;
                $template->matches = $this->model->getMatches()
                                 ->where('groupID', $group);

        if ($unplayed)
            $template->unplayed = $this->model->getMatches()->where(array(
                    'groupID' => $group,
                    'state' => 'ready' 
            ));
        else
            $template->unplayed = $this->model->getMatches()->where(array(
                    'groupID' => $group,
                    'state' => 'played' 
            ));

        $template->links = $unplayed;
 
        $template->group = $group;
        $template->names = $this->model->getTeams();

		$template->setFile(dirname(__FILE__) . '/MatchList.latte');
		$template->render();
	}

}
