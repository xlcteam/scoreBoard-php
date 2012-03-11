<?php

/**
 * Match presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class MatchPresenter extends SecuredPresenter 
{
        private $groups;

        private $events;
        
        public function renderDefault()
        {
                
        }
        
        public function renderGenerate($id = 0)
        {
                if ($id === 0)
                        return $this->renderDefault();

                $model = $this->getService('model');

                $group = $model
                        ->getGroups()
                        ->get($id);
                if (!$group) {
                        throw new NBadRequestException('Group not found');
                }

                $matches = $model->getMatches()->where('groupID', $group);

                if ($matches->count() !== 0) {
                        $this->template->msg = 'Some matches were generated before. There is no need to generate another.';
                } else {
                        $tmp = $model->getTeams()->where('groupID', $group)
                                                ->fetchPairs('id', 'name');

                        $teams = array();
                        foreach($tmp as $k=>$v){
                                $teams[] = array($k, $v); 
                        }


                        $combinations = $this->combinations($teams);
                        $this->template->msg = 'These combinations were generated:';

                        foreach($combinations as $combination) {
                                $matches->insert(array(
                                        'team1ID' => $combination[0][0],
                                        'team2ID' => $combination[1][0],
                                        'team1goals' => 0,
                                        'team2goals' => 0,
                                        'groupID' => $id,
                                        'userID' => $this->getUser()
                                                        ->getIdentity()->id
                                ));
                        }

                        $this->template->combinations = $combinations;
                }

        }

        public function renderList($id = 0)
        {
                if ($id === 0)
                        return $this->renderDefault();

                $model = $this->getService('model');

                $group = $model
                        ->getGroups()
                        ->get($id);
                if (!$group) {
                        throw new NBadRequestException('Group not found');
                }
 
                $matches = $this->getService('model')->getMatches();

                $rows = $matches->where('groupID', $id);
                $this->template->matches = $rows;
                $this->template->group = $group;

        }

        public function createComponentTeamList()
        {
                return new TeamList($this->getService('model'));
        }

        public function createComponentMatchList()
        {
                return new MatchList($this->getService('model'));
        }


        /*
         * Returns all possible two items combinations from an array
         */
        private function combinations($array) {
                $out = array();
                
                for($i = 0; $i < count($array); $i++) {
                        for($j = $i; $j < count($array); $j++) {
                                if($i !== $j)
                                        $out[] = array($array[$i], 
                                                        $array[$j]);
                        }
                }

                return $out;
        }


}
