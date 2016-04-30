<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index() {
    	session('uid', null);
    	session('urelation', null);
    	$this->display();
    }

    public function add() {
    	$code = substr(sha1(genRandStr()), 0, 15);
    	$time = time();
    	M('story')->add(array(
    		'name' => I('name'),
    		'code' => $code,
    		'createtime' => $time,
    		'updatetime' => $time
    		));

    	M('sentence')->add(array(
    		'code' => $code,
    		'parent_id' => -1,
    		'keyword' => '',
    		'content' => '我是'.I('name'),
    		'user_id' => -1,
    		'createtime' => $time
    		));
    	M('sentence')->add(array(
    		'code' => $code,
    		'parent_id' => -1,
    		'keyword' => '',
    		'content' => '好久不见',
    		'user_id' => -1,
    		'createtime' => $time
    		));

    	$this->redirect('Story/index', array('code'=>$code));
    }

    public function who() {
    	session('code', I('code'));
    	$this->story = M('story')->where(array('code'=>I('code')))->find();
    	$this->display();
    }

    public function who_handle() {
    	$count = M('user')->where(array('code'=>$_SESSION['code'], 'relation'=>I('relation')))->count();
    	if ($count == 0) {
    		$time = time();
    		M('user')->add(array(
    			'code' => $_SESSION['code'], 
    			'relation' => I('relation'),
    			'createtime' => $time,
    			'activetime' => $time
    			));
    	}
    	else {
    		M('user')->where(array('code' => $_SESSION['code'], 'relation' => I('relation')))->save(array('activetime'=>time()));
    	}
    	$user = M('user')->where(array('code'=>$_SESSION['code'], 'relation'=>I('relation')))->find();
    	session('urelation', I('relation'));
    	session('uid', $user['id']);
    	$url = $_SESSION['url'];
    	session('url', null);
    	$this->redirect($url);
    }
}