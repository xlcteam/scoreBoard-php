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
                                                        ->getIdentity()->id,
                                        'state' => 'ready'
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

        public function handleUpdate($id = 0)
        {
                if (!$this->isAjax())
                        return $this->renderDefault();

                if ($id === 0)
                        return $this->sendResponse(new JsonResponse(
                                array('error' => 'id not provided')
                        ));
                
                $matches = $this->getService('model')->getMatches();
                $match = $matches->get($id);

                if(!$match)
                        return $this->sendResponse(new JsonResponse(
                                array('error' => 'match not found')
                        ));

                $match['state'] = 'playing';

                $data = $this->request->post;
                $action = $data['action'];
                switch($action){
                        case 'team1_increase':
                                $match['team1goals'] = $match['team1goals'] + 1;
                                break;

                        case 'team2_increase':
                                $match['team2goals'] = $match['team2goals'] + 1;
                                break;
                        case 'team1_decrease':
                                $match['team1goals'] = ($match['team1goals'] > 0) ? ($match['team1goals'] - 1): 0;
                                break;

                        case 'team2_decrease':
                                $match['team2goals'] = ($match['team2goals'] > 0) ? ($match['team2goals'] - 1): 0;
                                break;

                        case 'finish':
                                $match['state'] = 'played';
                                break;



                }

/*
                switch($data['action']){
                        case 'team1_increase':
                                $match['team1goals']++;
                                break;

                        case 'team2_increase':
                                $match['team2goals']++;
                                break;
                        case 'team1_decrease':
                                $match['team1goals'] = ($match['team1goals'] > 0) ? ($match['team1goals'] - 1): 0;
                                break;

                        case 'team2_decrease':
                                $match['team2goals'] = ($match['team2goals'] > 0) ? ($match['team2goals'] - 1): 0;
                                break;

                        default:
                                break;
                }

 */
                $match->update();

                $this->sendResponse(new NJsonResponse(
                        array('success' => true)
                ));

        }

        public function renderPlay($id = 0)
        {
                if ($id === 0)
                        return $this->renderDefault();

                $model = $this->getService('model');
                $matches = $model->getMatches();

                $match = $matches->get($id);
                if(!$match)
                        throw new NBadRequestException('Match not found');

                if($match->state != 'ready')
                        return $this->renderDefault();

                $teams = $model->getTeams();

                $this->template->match = $match;
                $this->template->teams = $teams;
                $this->template->id = $id;
                $this->template->gid = $match['groupID'];

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
