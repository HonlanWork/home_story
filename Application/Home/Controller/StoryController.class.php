<?php
namespace Home\Controller;
use Think\Controller;
class StoryController extends Controller {
    public function index() {
    	$code = I('code');
    	$story = M('story')->where(array('code'=>$code))->find();
    	if(!isset($_SESSION['urelation'])) {
			$url = strstr($_SERVER["REQUEST_URI"], 'Home');
			session('url', $url);
			$this->redirect('Index/who', array('code'=>$code));
		}

    	$sentences = M('sentence')->where(array('code'=>I('code')))->select();
    	$data = [];
    	$flag = [];
    	for ($i = 0; $i < count($sentences); $i++) {
    		$tmp = $sentences[$i];
    		if (in_array($tmp['id'], $flag)) {
    			continue;
    		}
    		$data[] = $tmp;
    		$flag[] = $tmp['id'];

    		while (1) {
    			$has_child = false;
    			for ($j = 0; $j < count($sentences); $j++) { 
    				if (in_array($sentences[$j]['id'], $flag)) {
    					continue;
    				}
    				if ($sentences[$j]['parent_id'] == $tmp['id']) {
    					$has_child = true;
    					$tmp = $data[count($data) - 1]['content'];
    					$data[count($data) - 1]['content'] = substr($tmp, 0, strpos($tmp, $sentences[$j]['keyword'])).'<b class="active">'.$sentences[$j]['keyword'].'</b>'.substr($tmp, strpos($tmp, $sentences[$j]['keyword']) + strlen($sentences[$j]['keyword']));  
    					$data[] = $sentences[$j];
    					$flag[] = $sentences[$j]['id'];
    					$tmp = $sentences[$j];
    					break;
    				}
    			}
    			if (!$has_child) {
    				break;
    			}
    		}
    	}
    	$this->code = $code;
    	$this->sentences = $data;
    	$this->display();
    }

    public function add() {
    	$sentence = array(
    		'code' => I('code'),
    		'parent_id' => I('parent_id'),
    		'keyword' => I('keyword'),
    		'content' => I('content'),
    		'user_id' => $_SESSION['uid'],
    		'createtime' => time()
    		);
    	M('sentence')->add($sentence);
    	$sentence = M('sentence')->where($sentence)->find();
    	echo json_encode($sentence);
    }
}