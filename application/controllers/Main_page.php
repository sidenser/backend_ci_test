<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class Main_page extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        App::get_ci()->load->model('User_model');

        //TODO можно сделать через MW
        if (!User_model::is_logged())
        {
            $allowed = [
                'index',
                'login',
                'logout',
                'get_all_posts',
                'get_post',
            ];

            if (!in_array($this->router->fetch_method(), $allowed))
            {
                redirect('main_page/login');
            }
        }

        App::get_ci()->load
            ->model('Login_model')
            ->model('Post_model');

        if (is_prod())
        {
            die('In production it will be hard to debug! Run as development environment!');
        }
    }

    public function index()
    {
        App::get_ci()->load->view('main_page');
    }

    public function get_all_posts()
    {
        $posts =  Post_model::preparation(Post_model::get_all(), 'main_page');
        return $this->response_success(['posts' => $posts]);
    }

    public function get_post($post_id){ // or can be $this->input->post('news_id') , but better for GET REQUEST USE THIS

        $post_id = intval($post_id);

        if (empty($post_id)){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try
        {
            $post = new Post_model($post_id);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }


        $posts =  Post_model::preparation($post, 'full_info');
        return $this->response_success(['post' => $posts]);
    }


    public function comment($post_id){ // or can be App::get_ci()->input->post('news_id') , but better for GET REQUEST USE THIS ( tests )

        $message = $this->input->input_stream('message', true);
        $parent_id = $this->input->input_stream('parent_id', true);

        $post_id = intval($post_id);

        if (empty($post_id) || empty($message)){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try
        {
            //TODO оставлю это увы "CRM" в CI желает лучшего...
            // или я тупой или в этом чуде даже нет count))))
            $post = new Post_model($post_id);
        } catch (EmeraldModelNoDataException $ex){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_DATA);
        }

        $comment = Comment_model::create([
            'user_id' => 1,//User_model::get_session_id(),
            'assign_id' => $post_id,
            'text' => $message,
            'parent_id' => (int)$parent_id
        ]);

        return $this->response_success([
            'comment' => Comment_model::preparation($comment, 'full_info_one')
        ]);
    }

    public function login()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('login', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_data($this->input->input_stream(null, true));

        if ($this->form_validation->run() == FALSE){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try {
            $user = User_model::resolve_user_login(
                $this->input->input_stream('login', true),
                $this->input->input_stream('password', true));

            if (is_null($user)){
                return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
            }
            Login_model::start_session($user->get_id());
        }catch (Exception $exception) //TODO захватил (думаю тут не критично)
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        return $this->response_success(['user' => User_model::preparation($user, 'main_page')]);
    }


    public function logout()
    {
        Login_model::logout();
        redirect(site_url('/'));
    }

    public function add_money(){

        $this->load->library('form_validation');

        $this->form_validation->set_rules('sum', 'Sum', 'required|numeric');
        $this->form_validation->set_data($this->input->input_stream(null, true));

        if ($this->form_validation->run() == FALSE){
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        $user = User_model::get_user();

        try
        {
            $user->add_money((float)$this->input->input_stream('sum', true));
        }catch (RuntimeException $runtimeException)
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        return $this->response_success(['amount' => $user->get_wallet_balance()]);
    }


    public function buy_boosterpack()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('id', 'Id', 'required|numeric');
        $this->form_validation->set_data($this->input->input_stream(null, true));

        if ($this->form_validation->run() == FALSE)
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }
        $user = User_model::get_user();
        try
        {
            $amount = $user->buy_boosterpack((int)$this->input->input_stream('id'));

        }catch (RuntimeException $runtimeException)
        {
            return $this->response_error($runtimeException->getMessage().CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }
        return $this->response_success(['amount' => $amount]);
    }

    public function get_user()
    {
        $user = User_model::get_user();
        if(!$user->get_id())
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_NO_ACCESS);
        }

        return $this->response_success(['user' => User_model::preparation($user, 'main_page')]);
    }

    public function like()
    {
        $data = $this->input->input_stream(null, true);
        $this->load->library('form_validation');

        $this->form_validation->set_rules('entity', 'Entity', 'required');
        $this->form_validation->set_rules('id', 'Id', 'required');
        $this->form_validation->set_data($data);


        if ($this->form_validation->run() == FALSE || !in_array($data['entity'], ['post', 'comment']))
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        $user = User_model::get_user();

        if($user->get_likes() < 1)
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        try
        {
            $likes = 0;
            if($data['entity'] == 'post')
            {
                $likes = $user->like_post((int)$data['id']);
            }elseif ($data['entity'] == 'comment')
            {
                $likes = $user->like_comment((int)$data['id']);
            }

        }catch (RuntimeException $runtimeException)
        {
            return $this->response_error(CI_Core::RESPONSE_GENERIC_WRONG_PARAMS);
        }

        return $this->response_success(['likes' => $likes]); // Колво лайков под постом \ комментарием чтобы обновить
    }
}
