<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Biswadeep\FormTool\Core\Doc;
use Biswadeep\FormTool\Core\BluePrint;
use Biswadeep\FormTool\Core\DataModel;
use Biswadeep\FormTool\Core\Auth;
use Biswadeep\FormTool\Support\DTConverter;

class SettingsController extends AdminController
{
    // Required for Form Tool
    public $title = 'Settings';
    public $route = 'settings';

    private $crud = null;

    public function setup()
    {
        $model = new DataModel();
        $model->db('settings', 'id');

        $this->crud = Doc::create($this, $model, function(BluePrint $input) 
        {
            $timezones = DTConverter::getTimezones();

            $sampleDate = strtotime(\date('2022-07-15'));
            $sampleTime = strtotime(\date('16:45:45'));

            $dateFormats = [
                'd-m-Y',
                'd-m-y',
                'd/m/Y',
                'd/m/y',
                'd.m.Y',
                'd.m.y',
                'd M, Y',
                'd M, y',
                'd M Y',
                'd M y',
                'M d, Y',
                'M d, y',
                'M d Y',
                'M d y',
                'm-d-Y',
                'm-d-y',
            ];
            $timeFormats = [
                'h:i A',
                'h:i a',
                'h:iA',
                'h:ia',
                'H:i:s',
                'H:i',
            ];

            $dateOptions = $timeOptions = [];
            foreach ($dateFormats as $format) {
                $dateOptions[$format] = \date($format, $sampleDate);
            }
            foreach ($timeFormats as $format) {
                $timeOptions[$format] = \date($format, $sampleTime);
            }

            $input->text('appName', 'App Name')->required();
            $input->email('appEmail', 'Email');
            $input->text('appContact', 'Contact Number');
            $input->textarea('appAddress', 'Address');
            $input->image('appLogo', 'Logo')->path('settings');
            $input->image('appFavicon', 'Favicon')->path('settings');

            $input->select('timezone', 'Timezone')->required()->options($timezones)->plugin('chosen');
            $input->select('dateFormat', 'Date Format')->required()->options($dateOptions);
            $input->select('timeFormat', 'Time Format')->required()->options($timeOptions);

            $input->checkbox('isCaching', 'Caching')->captions('');

            $input->text('socialFacebook', 'Facebook Link');
            $input->text('socialLinkedIn', 'LinkedIn Link');
            $input->text('socialYoutube', 'Youtube Link');
            $input->text('socialTwitter', 'Twitter Link');
            $input->text('socialInstagram', 'Instagram Link');
            $input->text('socialPinterest', 'Pinterest Link');

            // $input->textarea('googleAnalyticsCode', 'Google Analytics Code');
            // $input->checkbox('dontTrackUs', "Don't Track Admin Users")->captions('');
        
        })->format('keyValue', 'default');
    }

    public function index()
    {
        $this->setup();

        $data['title'] = $this->title;

        $this->crud->edit();

        return $this->render('form-tool::form.index', $data);
    }

    public function update(Request $request)
    {
        $this->setup();

        return $this->crud->update();
    }
}
