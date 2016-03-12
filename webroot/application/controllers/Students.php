<?php defined("BASEPATH") or exit("No direct script access allowed");

/**
* 	Controls the profile page, the enroll page and the schedule page
*/
class Students extends App_Base_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

    /**
     * Loads the student profile page
     */
	public function profile()
    {
        $this->load->model('student');

        //Loading header
        $data['info_bar'] = 'AND HIS NAME IS JOHN CENA';
		$this->load->view('layouts/header.php', $data);

        //Loading content
		$data['info'] = $this->student->getInfo();
		$data['record'] = $this->student->getRecord();
		$this->load->view('student/profile.php',$data);

        $this->load->view('layouts/footer.php', $data);
	}

	/**
	 * Loads the student enroll page
	 */
	public function enroll($semester_url)
	{
		//Loading models
		$this->load->model('semester');
		$this->load->model('scheduler');

		//Validating if semester name url exist. If not, redirect to main page.
		if(!$semester = $this->semester->getBySlug($semester_url))
			redirect(base_url());

		//If there the semester cookie already exists then load data from that of init a new scheduler object.
		if($this->session->userdata($semester_url) == NULL)
		{
			//Initializing the scheduler because the cookie doesn't exist.
			$this->scheduler->init($semester->id);

			//After initializing the scheduler, it save the data into a session cookie.
			$this->session->set_userdata($semester_url, serialize($this->scheduler));
		}

		//Preparing data for view
		$data['title'] = $semester->name;
		$data['info_bar'] = 'Register in three simple steps. 1. Pick your courses 2. Generate 3. Commit!';

		$data['semester_name'] = $data['title'];
		$data['ajax_route'] = base_url('students/ajax/'.$semester_url);
		$data['add_js'] = ['schedule.js', 'enroll.js'];

		$this->load->view('layouts/header.php', $data);
		$this->load->view('student/scheduler.php', $data);
		$this->load->view('layouts/footer.php', $data);
	}

	public function ajax($semester_url, $action){
		$this->load->model('scheduler');

		//Continue work on the scheduler model
		$this->scheduler = unserialize($this->session->userdata($semester_url));

		//Actions that can be performed to the scheduler object start here with
		switch ($action):

			//Returns information of preferences and the schedule.
			case 'load': {
				echo $this->scheduler->getMainSchedule();
			} break;

			case 'search-list': {
				echo $this->scheduler->searchCourseList();
			} break;

			case 'add-course': {
				$course = $this->input->post('input', TRUE);

				echo $this->scheduler->add_course($course);
			} break;

			case 'auto-pick':{

				echo $this->scheduler->auto_pick_course();

			} break;

			case 'commit':{
				$new_schedule = $this->input->post('input', TRUE);
				if($this->scheduler->apply_new_schedule($new_schedule))
					echo 'x';
			}break;

			//Returns a list of possible schedules.
			case 'generate': {
				$schedules = $this->scheduler->generateSchedules();

				echo json_encode($schedules,  JSON_NUMERIC_CHECK);
			} break;

			case 'drop': {
				$hash_id = $this->input->post('input', TRUE);
				$section = $this->scheduler->drop($hash_id);

				echo $section;
			} break;

			case 'undo-drop': {

				$section = $this->input->post('input');
				$response = $this->scheduler->undo_drop($section);

				echo ($response)? 'Re-added section to schedule': 'Failed at re-adding section to schedule';
			} break;

			case 'remove-course': {
				$course_id = $this->input->post('input', TRUE);

				echo $this->scheduler->remove_from_generator($course_id);
			} break;

			case 'course-list': {
				echo $this->scheduler->get_course_list();
			} break;

			case 'add-preference': {
				$json_input = $this->input->post('input', TRUE);
				$message = $this->scheduler->addTimePreference($json_input);

				echo $message;
			} break;

			case 'remove-preference': {
				$hash_code = $this->input->post('input', TRUE);
				$message = $this->scheduler->removeTimePreference($hash_code);

				echo $message;
			} break;

			case 'get-preference': {
				echo $this->scheduler->getTimePreferences();
			} break;

			case 'reset': {
				$this->session->unset_userdata($semester_url);
				return;
			}

		endswitch;

		//Serialize the scheduler object model back to the cookie.
		$this->session->set_userdata($semester_url, serialize($this->scheduler));
	}

	/**
	 * Loads the schedule of a semester by id
	 * @param $semester
	 */
	public function view($semester_url)
	{
		$this->load->model('semester');
		$this->load->model('scheduler');

		if(!$semester = $this->semester->getBySlug($semester_url))
			redirect(base_url());

		//If there the semester cookie already exists then load data from that of init a new scheduler object.
		if($this->session->userdata($semester_url) == NULL)
		{
			//Initializing the scheduler because the cookie doesn't exist.
			$this->scheduler->init($semester->id);
			$this->session->set_userdata($semester_url, serialize($this->scheduler));
		}

		$this->scheduler = unserialize($this->session->userdata($semester_url));

		$data['info_bar'] = 'Schedule for '.$semester->name;
		$data['title'] = 'Schedule of '.$semester->name;

		$data['schedule'] = $this->scheduler->getMainSchedule();

		$this->load->view('layouts/header.php', $data);
		$this->load->view('student/view_schedule.php', $data);
		$this->load->view('layouts/footer.php');
	}

}
?>
