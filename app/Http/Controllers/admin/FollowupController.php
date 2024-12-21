<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Followup;
use App\Models\admin\Source_lead;
use App\Models\admin\Service;
use App\Models\admin\Duration;
use App\Models\admin\Frequency;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Reportexcelclass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Helper;
use Image;
// use Dompdf\Dompdf;
use PDF;
use Mail;

class FollowupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $user = Auth::user();
        // echo"<pre>";print_r($user->toArray());echo"</pre>";exit;
        $startdate = $request->s_date;
        $enddate = $request->e_date;
        $salesmanname = $request->salesmanname;
        $servicename = $request->servicename;
        $user_data = Auth::user();
        $query = DB::table('followups')->where('accept_reject',0);
        if($user_data->role_id != 1 && $user_data->role_id != 7){
            $query = $query->where('salesman_id', $user_data->id);
        }
        if($user_data->role_id == 7){
            $query = $query->where('surveyor', $user_data->id);
        }
        // echo"<pre>";print_r($user_data->id);echo"</pre>";exit;
        if ($startdate !='')
        {
            $query = $query->where('added_date', '>=', date('Y-m-d', strtotime($startdate)));
            //$query=$query->where('created_at', $startdate);
        }
        if ($enddate !='')
        {
            $query = $query->where('added_date', '<=', date('Y-m-d', strtotime($enddate)));
            //$query=$query->where('created_at', $startdate);
        }
        $data['startdate'] =$startdate;
        $data['enddate'] =$enddate;
        if ($salesmanname !='')
        {
            $query=$query->where('salesman_id', $salesmanname);
        }
        if ($servicename !='')
        {
            $query=$query->where('service_id', $servicename);
        }
        $data['filter_salep_id'] =$salesmanname;
        $data['filter_service_id'] =$servicename;
        // $data['salesman_data'] = DB::table('users')->Where('role_id',7)->get();
        $data['salesman_data'] = DB::table('users')->Where('id','!=', 1)->get();
        $data['service_data'] = DB::table('services')->get();
        $data['followup_status'] = DB::table('followup_status')->get();
        $data['followup_data']=$query->orderBy('id','DESC')->get();
        // echo $query->toSql();
        //  echo'<pre>';print_r( $data['followup_data']);echo'</pre>';exit;
        return view('admin.list_followup',$data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['sourcelead_data']= Source_lead::orderBy('id','DESC')->get();
        $data['service_data']= Service::orderBy('id','DESC')->get();
        $data['salesman_data'] = DB::table('users')->Where('id','!=', 1)->get();
        $data['country_data']= DB::table('countries')->get();
        $data['branch_data']= DB::table('branch')->get();
        $data['services_required'] = DB::table('services_required')->get();
        $data['goods_description'] = DB::table('goods_description')->get();
        $data['surveyor'] = DB::table('users')->where('role_id','=',7)->where('surveyor','1')->get();
        $data['surveyor_type'] = DB::table('surveyor_type')->get();
        $data['customer_type'] = DB::table('customer_type')->get();
        $data['title_rank'] = DB::table('title_rank')->get();
        $data['storage_type'] = DB::table('storage_type')->get();
        $data['storage_mode'] = DB::table('storage_mode')->get();
        $data['enquiry_mode'] = DB::table('enquiry_mode')->get();
        $data['duration_data'] = Duration::all();
        $data['frequency_data'] = Frequency::all();
        return view('admin.add_followup',$data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo"<pre>";print_r($request->all());echo"</pre>";exit;
        $followup= new Followup;
        if($request->agent_id !=''){
            $data['agent_id']      = $request->agent_id;
        }
        if($request->attr_id !=''){
            $data['attr_id']      = $request->attr_id;
        }
    $data['customer_type']      = $request->customer_type;
    $data['branch']      = $request->branch;
    $data['enquiry_date']      = date('Y-m-d', strtotime($request->enquiry_date));
    if($request->client_box != ''){
        $data['client_box']      = '0';
    }else{
        $data['client_box']      = '1';
    }
    $data['company_name_id']      = $request->company_name_id;
    $data['company_name']      = $request->company_name;
    $data['title_rank']      = $request->title_rank;
    $data['agent_attr_data']      = $request->agent_attr_data;
    $data['customer_name']      = $request->customer_name;
    $data['customer_phone1']      = $request->customer_phone1;
    $data['customer_phone2']      = $request->customer_phone2;
    $data['customer_email']      = $request->customer_email;
    $data['salesman_id']      = $request->salesman_id ?? "";
    $data['address']      = $request->address;
    $data['associate']      = $request->associate;
    if($request->customer_form != ''){
        $data['customer_form']      = '0';
    }else{
        $data['customer_form']      = '1';
    }
    $data['customer_title_rank']      = $request->customer_title_rank;
    $data['f_name']      = $request->f_name;
    $data['m_name']      = $request->m_name;
    $data['l_name']      = $request->l_name;
    $data['c_mobile']      = $request->c_mobile;
    $data['c_phone']      = $request->c_phone;
    $data['c_email']      = $request->c_email;
    $data['c_add']      = $request->c_add;
    $data['c_country']      = $request->c_country;
    $data['c_city']      = $request->c_city;
    if($request->origin_desti_move != ''){
        $data['origin_desti_move']      = '0';
    }else{
        $data['origin_desti_move']      = '1';
    }
    $data['service_id']      = $request->service_id;
    $data['service_required']      = $request->service_required;
    $data['service_req_val']      = $request->service_req_val;
    $data['desc_of_goods']      = $request->desc_of_goods;
    $data['input_goods']      = $request->input_goods;
    $data['survey_req']      = $request->survey_req;
    $data['survey_type']      = $request->survey_type;
    $data['s_date']      = date('Y-m-d', strtotime($request->s_date));
    $data['origin_add']      = $request->origin_add;
    $data['origin_country']      = $request->origin_country;
    $data['origin_state']      = $request->origin_state;
    $data['origin_city']      = $request->origin_city;
    $data['origin_location']      = $request->origin_location;
    $data['origin_zip_post']      = $request->origin_zip_post;
    $data['desti_add']      = $request->desti_add;
    $data['desti_country']      = $request->desti_country;
    $data['desti_state']      = $request->desti_state;
    $data['desti_city']      = $request->desti_city;
    $data['desti_location']      = $request->desti_location;
    $data['desti_zip_post']      = $request->desti_zip_post;
    if($request->storage_details != ''){
        $data['storage_details']      = '0';
    }else{
        $data['storage_details']      = '1';
    }
    $data['storage_id']      = $request->storage_id;
    $data['frequency']      = $request->frequency;
    $data['duration']      = $request->duration;
    $data['billing_mode']      = $request->billing_mode;
    $data['duration']      = $request->duration;
    $data['storage_mode']      = $request->storage_mode;
    $data['storage_product_type']      = $request->storage_product_type;
    if($request->allowance_details != ''){
        $data['allowance_details']      = '0';
    }else{
        $data['allowance_details']      = '1';
    }
    $data['road_input']      = $request->road_input;
    $data['road_cft_net']      = $request->road_cft_net;
    $data['air_input']      = $request->air_input;
    $data['air_lbs_net']      = $request->air_lbs_net;
    $data['sea_input']      = $request->sea_input;
    $data['sea_cft_net']      = $request->sea_cft_net;
    $data['rail_input']      = $request->rail_input;
    $data['rail_cft_net']      = $request->rail_cft_net;
    if($request->general_info_details != ''){
        $data['general_info_details']      = '0';
    }else{
        $data['general_info_details']      = '1';
    }
    $data['payment_by']      = $request->payment_by;
    $data['sourcelead_id']      = $request->sourcelead_id;
    $data['enquiry_mode']      = $request->enquiry_mode;
    $data['status_id']      = $request->status_id;
    $data['completed_date']      = date('Y-m-d', strtotime($request->completed_date));
    $data['rmc']      = $request->rmc;
    $data['assign_to']      = $request->assign_to;
    $data['sales_notes']      = $request->sales_notes;
    $data['surveyor']      = $request->surveyor;
    $data['inquiry_type']      = $request->inquiry_type;
    $data['inquiry_value']      = $request->inquiry_value;
    $data['inquiry_date']      = date('Y-m-d', strtotime($request->inquiry_date));
    $data['move_type']      = $request->move_type;
    $data['move_value']      = $request->move_value;
    $data['move_date']      = date('Y-m-d', strtotime($request->move_date));
    $data['volume']      = $request->volume;
    $data['added_date']      = date('Y-m-d');
    $id = DB::table('followups')->insertGetId($data);
    if($id !=""){
        $datau['quote_no']      = 'QSR-'.$id;
        DB::table('followups')->where('id',$id)->update($datau);
    }
        return  redirect()->route('followup.index')->with('success', 'Inquiry  added successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Followup $followup)
    {
        $data['sourcelead_data']= Source_lead::orderBy('id','DESC')->get();
        $data['service_data']= Service::orderBy('id','DESC')->get();
        $data['salesman_data'] = DB::table('users')->Where('id','!=', 1)->get();
        $data['country_data']= DB::table('countries')->get();
        $data['branch_data']= DB::table('branch')->get();
        $data['services_required'] = DB::table('services_required')->get();
        $data['goods_description'] = DB::table('goods_description')->get();
        $data['surveyor'] = DB::table('users')->where('role_id','=',7)->where('surveyor','1')->get();
        $data['surveyor_type'] = DB::table('surveyor_type')->get();
        $data['customer_type'] = DB::table('customer_type')->get();
        $data['title_rank'] = DB::table('title_rank')->get();
        $data['storage_type'] = DB::table('storage_type')->get();
        $data['storage_mode'] = DB::table('storage_mode')->get();
        $data['enquiry_mode'] = DB::table('enquiry_mode')->get();
        $data['duration_data'] = Duration::all();
        $data['frequency_data'] = Frequency::all();
        return view('admin.edit_followup',compact('followup'),$data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $followup = Followup::find($id);
        $followup->quote_no= $request->quote_no;
        $followup->customer_type= $request->customer_type;
        $followup->branch= $request->branch;
        $followup->enquiry_date= date('Y-m-d', strtotime($request->enquiry_date));
        if($request->client_box != ''){
            $followup->client_box      = '0';
        }else{
            $followup->client_box      = '1';
        }
        $followup->company_name_id      = $request->company_name_id; $followup->company_name      = $request->company_name;
        // $followup->company_name      = $request->search_company;
        $followup->title_rank      = $request->title_rank;
        $followup->agent_attr_data      = $request->agent_attr_data;
        $followup->customer_name      = $request->customer_name;
        $followup->customer_phone1      = $request->customer_phone1;
        $followup->customer_phone2      = $request->customer_phone2;
        $followup->customer_email      = $request->customer_email;
        $followup->salesman_id      = $request->salesman_id ?? "";
        $followup->address      = $request->address;
        $followup->associate      = $request->associate;
        if($request->customer_form != ''){
            $followup->customer_form      = '0';
        }else{
            $followup->customer_form      = '1';
        }
        $followup->customer_title_rank      = $request->customer_title_rank;
        $followup->f_name      = $request->f_name;
        $followup->m_name      = $request->m_name;
        $followup->l_name      = $request->l_name;
        $followup->c_mobile      = $request->c_mobile;
        $followup->c_phone      = $request->c_phone;
        $followup->c_email      = $request->c_email;
        $followup->c_add      = $request->c_add;
        $followup->c_country      = $request->c_country;
        $followup->c_city      = $request->c_city;
        if($request->origin_desti_move != ''){
            $followup->origin_desti_move      = '0';
        }else{
            $followup->origin_desti_move      = '1';
        }
        $followup->service_id      = $request->service_id;
        $followup->service_required      = $request->service_required;
        $followup->service_req_val      = $request->service_req_val;
        $followup->desc_of_goods      = $request->desc_of_goods;
        $followup->input_goods      = $request->input_goods;
        if($request->survey_req != ''){
            $followup->survey_req      = '0';
        }else{
            $followup->survey_req      = '1';
        }
        $followup->survey_type      = $request->survey_type;
        $followup->s_date      = date('Y-m-d', strtotime($request->s_date));
        $followup->origin_add      = $request->origin_add;
        $followup->origin_country      = $request->origin_country;
        $followup->origin_state      = $request->origin_state;
        $followup->origin_city      = $request->origin_city;
        $followup->origin_location      = $request->origin_location;
        $followup->origin_zip_post      = $request->origin_zip_post;
        $followup->desti_add      = $request->desti_add;
        $followup->desti_country      = $request->desti_country;
        $followup->desti_state      = $request->desti_state;
        $followup->desti_city      = $request->desti_city;
        $followup->desti_location      = $request->desti_location;
        $followup->desti_zip_post      = $request->desti_zip_post;
        if($request->storage_details != ''){
            $followup->storage_details      = '0';
        }else{
            $followup->storage_details      = '1';
        }
        $followup->storage_id      = $request->storage_id;
        $followup->frequency      = $request->frequency;
        $followup->duration      = $request->duration;
        $followup->billing_mode      = $request->billing_mode;
        $followup->duration      = $request->duration;
        $followup->storage_mode      = $request->storage_mode;
        $followup->storage_product_type      = $request->storage_product_type;
        if($request->allowance_details != ''){
            $followup->allowance_details      = '0';
        }else{
            $followup->allowance_details      = '1';
        }
        $followup->road_input      = $request->road_input;
        $followup->road_cft_net      = $request->road_cft_net;
        $followup->air_input      = $request->air_input;
        $followup->air_lbs_net      = $request->air_lbs_net;
        $followup->sea_input      = $request->sea_input;
        $followup->sea_cft_net      = $request->sea_cft_net;
        $followup->rail_input      = $request->rail_input;
        $followup->rail_cft_net      = $request->rail_cft_net;
        if($request->general_info_details != ''){
            $followup->general_info_details      = '0';
        }else{
            $followup->general_info_details      = '1';
        }
        $followup->payment_by     = $request->payment_by;
        $followup->sourcelead_id  = $request->sourcelead_id;
        $followup->enquiry_mode   = $request->enquiry_mode;
        $followup->status_id      = $request->status_id;
        $followup->completed_date = date('Y-m-d', strtotime($request->completed_date));
        $followup->rmc            = $request->rmc;
        $followup->assign_to      = $request->assign_to;
        $followup->sales_notes    = $request->sales_notes;
        $followup->surveyor       = $request->surveyor;
        $followup->inquiry_type   = $request->inquiry_type;
        $followup->inquiry_value  = $request->inquiry_value;
        $followup->inquiry_date   = date('Y-m-d', strtotime($request->inquiry_date));
        $followup->move_type      = $request->move_type;
        $followup->move_value     = $request->move_value;
        $followup->move_date      = date('Y-m-d', strtotime($request->move_date));
        $followup->volume         = $request->volume;
        $followup->added_date     = date('Y-m-d');
        $followup->save();
        return  redirect()->route('followup.index')->with('success', 'Inquiry updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request  $request)
    {
        $delete_id = $request->selected;
        DB::table('followups')->whereIn('id',$delete_id)->delete();
        return redirect()->route('followup.index')->with('success','Inquiry deleted successfully.');
    }
    function followup_form(){
        $data['inquiry_id'] = $_POST['inquiry_id'];
        $data['follow_up_date'] = date('Y-m-d', strtotime($_POST['date'])); ;
        $data['next_follow_up_date'] = date('Y-m-d', strtotime($_POST['next_date'])); ;
        $data['remarks'] = $_POST['remarks'];
        //echo "<pre>";print_r($data);echo"</pre>";
        DB::table('follow_up_date')->insert($data);
        return redirect()->route('followup.index')->with('success','Follow update successfully.');
        //echo "<pre>";print_r($_POST);echo"</pre>";exit;
    }
    public function filter_data(Request $request)
    {
        $startdate = $request->startdate_fil;
        $enddate = $request->enddate_fil;
        $salesmanname = $request->filter_salep_id_fil;
        $servicename = $request->filter_service_id_fil;
        $user_data = Auth::user();
        $query = DB::table('followups')->where('accept_reject',0);
        if($user_data->role_id != 1){
            $query = $query->where('salesman_id', $user_data->id);
        }
        if ($startdate !='')
        {
            // $query = $query->where('added_date', '>=', $startdate);
            $query = $query->where('added_date', '>=', date('Y-m-d', strtotime($startdate)));
        }
        if ($enddate !='')
        {
            // $query = $query->where('added_date', '<=',$enddate);
            $query = $query->where('added_date', '<=', date('Y-m-d', strtotime($enddate)));
        }
        if ($salesmanname !='')
        {
            $query=$query->where('salesman_id', $salesmanname);
        }
        if ($servicename !='')
        {
            $query=$query->where('service_id', $servicename);
        }
        $data =$query->orderBy('id','DESC')->get();
//         $sql = $query->toSql();
// dd($sql);exit;
        //echo "<pre>";print_r($data);echo"</pre>";exit;
        //return Excel::download(new Reportexcelclass($data), 'filtered_data.xlsx');
        $customHeadings = ['Quote No', 'Source Lead','Local/Storage/Export','Salesman Name','Inquiry Date','Move Date','Volume','Customer Name','Customer Email','Customer phone','origin','Destination'];
       // $customValues = ['Custom Value 1', 'Custom Value 2'];
       // $customValues = $data->pluck('quote_no','sourcelead_id')->toArray();
        return Excel::download(new Reportexcelclass($data, $customHeadings, null), 'filtered_data.xlsx');
    }
    function status_change(){
        //echo "<pre>";print_r($_POST);echo"</pre>";exit;
        $id = $_POST['inquiry_id'];
        $data['status'] = $_POST['inquiry_status'];
        DB::table('followups')->where('id',$id)->update($data);
        return  redirect()->route('followup.index')->with('success', 'Inquiry updated successfully');
    }
    function surveyor_form(Request $request,$id){
        // echo"<pre>";
        // print_r($data['surveyor_data']);
        // echo"</pre>";exit;
        $data['followup_data'] =  DB::table('followups')->where('id',$id)->first();
        $data['surveyor'] = DB::table('users')->where('role_id','7')->where('surveyor','1')->get();
        $data['survey_assign']= DB::table('survey_assign')->where('enquiry_id',$id)->first();
        $data['surveyor_time_zone']= DB::table('surveyor_time_zone')->get();
        $data['country_data']= DB::table('countries')->get();
        return view('admin.surveyor_form',$data);
    }
    function item_form_data(Request $request){
        //echo"<pre>";print_r($request->file('image'));echo"</pre>";exit;
        $data['name'] = $request->name;
        if($request->hasfile('image') != ''){
            $image = $request->file('image');
            $remove_space = str_replace(' ', '-', $image->getClientOriginalName());
            $data['image'] = time().$remove_space;
            $destination_path = public_path('/upload/custome_item/small');
            $img = Image::make($image->path());
            $width = 70;
            $height = 70;
            $img->resize($width,$height,function($contrainst){
            })->save($destination_path."/".$data['image']);
            $image = $data['image'];
                $data['image']  = $image;
            }else{
                $data['image'] = "";
            }
        $data['quantity'] = $request->quantity;
        $data['volume'] = $request->volume;
        $data['value'] = $request->value;
        $data['item_condition'] = $request->item_condition;
        $data['assembly'] = $request->assembly;
        $data['crating'] = $request->crating;
        $data['weight'] = $request->weight;
        $data['height'] = $request->height;
        $data['density'] = $request->density;
        // echo"<pre>";print_r($data);echo"</pre>";exit;
        DB::table('custome_item')->insert($data);
        return redirect()->route('luggage_item')->with('success', 'Custome item Added Successfully');
    }
    function luggage_item(Request $request){
        $data['condition_data']= DB::table('item_condition')->get();
        $data['custome_item_data']= DB::table('custome_item')->get();
        return view('admin.luggage_item_form',$data);
    }
    function selected_items(Request $request){
        // echo"<pre>";print_r($request->all());echo"</pre>";exit;
    }
    function followup_data(){
        $id = $_POST['id'];
        $data = DB::table('follow_up_date')->where('inquiry_id',$id)->orderBy('id','desc')->get();
        $html="";
        if(count($data) > 0 && isset($data)){
            $html.= '<div class="table-responsive mb-30" style="
            margin-bottom: 40px;
        ">';
            $html.='<table class="table mb-30">';
            $html.='<thead><tr><th>Follow Up Date</th><th>Next Follow Up Date</th><th>Remark </th></tr></thead>';
                    $html.='<tbody>';
                    foreach($data as $data_data){
                        $html.='<tr>
                        <td>'.$data_data->follow_up_date.'</td>
                        <td>'.$data_data->next_follow_up_date.'</td>
                        <td>'.$data_data->remarks.'</td>
                        </tr>';
                    }
                    $html.='</tbody>';
            $html.="</tbody>";
            $html.="</div>";
        }
        echo $html;
    }
    public function get_quote_form($id)
    {
        $data['inquiry_id'] = $id;
        $data['goods_description'] = DB::table('goods_description')->get();
        $data['transport_mode'] = DB::table('transport_mode')->get();
        $data['services_required'] = DB::table('services_required')->get();
        $data['followups_data'] = DB::table('followups')->where('id', $id)->first();
        //echo "<pre>";print_r($data['quote_data']);echo "</pre>";exit;
        return view('admin.add_get_quote',$data);
    }
    public function survey_info($id)
    {
        $data['inquiry_id'] = $id;
        $data['services_required'] = DB::table('services_required')->get();
        $data['surveyor_data'] = DB::table('users')->where('role_id','=',7)->where('surveyor','1')->get();
        $data['surveyor_type'] = DB::table('surveyor_type')->get();
        // $data['followups_data'] = DB::table('followups')->where('id', $id)->first();
        // echo "<pre>";print_r($data['inquiry_id']);echo "</pre>";exit;
        return view('admin.add_survey_info',$data);
    }
    public function survey_information(Request $request){
        $inquiry_id = $request->inquiry_id;
        $data['enquiry_id'] = $request->inquiry_id;
        $data['survey_id'] = $request->survey_id;
        $data['survey_company_name'] = $request->survey_company_name;
        $data['survey_customer_name'] = $request->survey_customer_name;
        $data['survey_customer_mobile'] = $request->survey_customer_mobile;
        $data['survey_customer_address'] = $request->survey_customer_address;
        $data['survey_type'] = $request->survey_type;
        $data['time_from'] = $request->time_from;
        $data['time_to'] = $request->time_to;
        $data['survey_date'] = $request->survey_date;
        if($request->surveyor_name !="" && !empty($request->surveyor_name)){
            $data['surveyor_name'] = $request->surveyor_name;
        }else{
            $data['surveyor_name'] = "";
        }
        $time_zone_id_key = 'surveyor_time_zone_' . $data['surveyor_name'];
        if ($request->has($time_zone_id_key)) {
            $data['surveyor_time_zone'] = $request->$time_zone_id_key;
        } else {
            $data['surveyor_time_zone'] = null;
        }
        $survey_email= DB::table('users')->where('id',$data['surveyor_name'])->pluck('email')->first();
        //    echo "<pre>";print_r($data);echo"</pre>";exit;
        $survey_data = DB::table('survey_assign')->where('enquiry_id',$data['enquiry_id'])->first();
        if($survey_data !=''){
           // echo "<pre>";print_r($data);echo"</pre>";exit;
           // echo "If";exit;
           // echo "<pre>";print_r($_POST['surveyor_name']);echo"</pre>";exit;
           $original_survey_type = $survey_data->survey_type;
           $original_surveyor = $survey_data->surveyor_name;
           if ($original_survey_type != $data['survey_type'] || $original_surveyor != $data['surveyor_name']) {
            DB::table('followups')
                ->where('id', $data['enquiry_id'])
                ->update(['survey_type' => $data['survey_type'],
                            'surveyor' => $data['surveyor_name']]);
        }
          DB::table('survey_assign')->where('enquiry_id', $data['enquiry_id'])->update($data);
          $html = '<!doctype html> <html>
          <head>
              <meta charset="utf-8">
              <title></title>
              <style>
                  .logo {
                      text-align: center;
                      width: 100%;
                        }
                  .wrapper {
                      width: 100%;
                      max-width:500px;
                      margin:auto;
                      font-size:14px;
                      line-height:24px;
                      font-family:Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif;
                      color:#555;
                  }
                  .wrapper div {
                      height: auto;
                      float: left;
                      margin-bottom: 15px;
                      width:100%;
                  }
                  .text-center {
                      text-align: center;
                  }
                  .email-wrapper {
                      padding:5px;
                      border:1px solid #ccc;
                      width:100%;
                  }
                  .big {
                      text-align: center;
                      font-size: 26px;
                      color: #e31e24;
                      font-weight: bold;
                      margin-bottom: 0 !important;
                      text-transform: uppercase;
                      line-height: 34px;
                  }
                  .welcome {
                      font-size: 17px;
                      font-weight: bold;
                  }
                  .footer {
                      text-align: center;
                      color: #999;
                      font-size: 13px;
                  }
              </style>
          </head>
          <body>
              <div class="wrapper" >
                  <div class="email-wrapper" >
                      <table style="border-collapse:collapse;" width="100%" border="0" cellspacing="0" cellpadding="10">
                          <tr>
                              <td>
                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                      <tr>
                                          <td style="font-size:18px;">Hello ,</td>
                                      </tr>
                                      <tr>
                                          <td style="line-height:20px;">
                                             Please find the below Payment details
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  <table style="border-top:3px solid #333;" bgcolor="#f7f7f7" width="100%" border="0" cellspacing="0" cellpadding="5">
                                      <tr>
                                          <td width="50%">
                                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                <tr>
                                                <td width="100px">Name: </td>
                                                <td>'.$data['survey_customer_name'].'</td>
                                                <td>Surveyor Portal:</td>
                                                <td><a href="'.url("/admin").'" style="width: 100%;color: #555;display: inline-block;">Surveyor Portal</a></td>
                                                </tr>
                                                <a >
                                              </table>
                                          </td>
                                      </tr>
                                  </table>
                              </td>
                          </tr>
                      </table>
                  </div>
              </div>
          </body>
      </html>';
       $subject = "New Survey Reuqest-";
           $user_mail = $survey_email;
           $admin = 'mayudin.hnrtechnologies@gmail.com';
           $to = [$user_mail];
           Mail::send([], [], function ($message) use ($html, $to, $subject) {
            $message->to($to);
            $message->subject($subject);
            $message->from('devang.hnrtechnologies@gmail.com', 'ERP');
            $message->html($html);
        });
        return  redirect()->route('followup.index')->with('success', 'Survey Information Updated Successfully');
        }else{
            //echo "else";exit;
            // echo "<pre>";print_r($data);echo"</pre>";exit;
            // DB::table('survey_information')->insert($data);
           DB::table('survey_assign')->insert($data);
           $html = '<!doctype html> <html>
       <head>
           <meta charset="utf-8">
           <title></title>
           <style>
               .logo {
                   text-align: center;
                   width: 100%;
                     }
               .wrapper {
                   width: 100%;
                   max-width:500px;
                   margin:auto;
                   font-size:14px;
                   line-height:24px;
                   font-family:Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif;
                   color:#555;
               }
               .wrapper div {
                   height: auto;
                   float: left;
                   margin-bottom: 15px;
                   width:100%;
               }
               .text-center {
                   text-align: center;
               }
               .email-wrapper {
                   padding:5px;
                   border:1px solid #ccc;
                   width:100%;
               }
               .big {
                   text-align: center;
                   font-size: 26px;
                   color: #e31e24;
                   font-weight: bold;
                   margin-bottom: 0 !important;
                   text-transform: uppercase;
                   line-height: 34px;
               }
               .welcome {
                   font-size: 17px;
                   font-weight: bold;
               }
               .footer {
                   text-align: center;
                   color: #999;
                   font-size: 13px;
               }
           </style>
       </head>
       <body>
           <div class="wrapper" >
               <div class="email-wrapper" >
                   <table style="border-collapse:collapse;" width="100%" border="0" cellspacing="0" cellpadding="10">
                       <tr>
                           <td>
                               <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                   <tr>
                                       <td style="font-size:18px;">Hello ,</td>
                                   </tr>
                                   <tr>
                                       <td style="line-height:20px;">
                                          Please find the below Payment details
                                       </td>
                                   </tr>
                               </table>
                           </td>
                       </tr>
                       <tr>
                           <td>
                               <table style="border-top:3px solid #333;" bgcolor="#f7f7f7" width="100%" border="0" cellspacing="0" cellpadding="5">
                                   <tr>
                                       <td width="50%">
                                           <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                               <tr>
													 <td width="100px">Name: </td>
                                                <td>'.$data['survey_customer_name'].'</td>
                                                <td>Surveyor Portal:</td>
                                                <td><a href="'.url("/admin").'" style="width: 100%;color: #555;display: inline-block;">Surveyor Portal</a></td>
												</tr>
                                           </table>
                                       </td>
                                   </tr>
                               </table>
                           </td>
                       </tr>
                   </table>
               </div>
           </div>
       </body>
   </html>';
           $subject = "New Survey Reuqest-";
           $user_mail = $survey_email;
           $admin = 'mayudin.hnrtechnologies@gmail.com';
           $to = [$user_mail];
           Mail::send([], [], function ($message) use ($html, $to, $subject) {
            $message->to($to);
            $message->subject($subject);
            $message->from('devang.hnrtechnologies@gmail.com', 'ERP');
            $message->html($html);
        });
            return  redirect()->route('followup.index')->with('success', 'Survey Information Added Successfully');
        }
    }
    public function getTimeZones(Request $request)
    {
        // echo"<pre>";print_r($request->all());echo"</pre>";exit;
    $surveyorId = $request->input('surveyor_id');
    $surveyDate = $request->input('survey_date');
    $surveyorName = $request->input('surveyor_name');
    // $surveyor_data = DB::table('surveyor')->where('id', $surveyorName)->first();
    $surveyor_data_old = DB::table('users')->where('role_id','=',7)->where('surveyor','1')->get()->toArray();
    $surveyorName_new = DB::table('survey_assign')
                        ->where('survey_date', $surveyDate)
                        ->select('surveyor_name', 'surveyor_time_zone')
                        ->get()
                        ->groupBy('surveyor_name')
                        ->mapWithKeys(function($group, $key) {
                            return [$key => $group->pluck('surveyor_time_zone')->toArray()];
                        })
                        ->toArray();
    // echo"<pre>";print_r($surveyorName_new);echo"</pre>";exit;
    $html = '';
    // $html .= '<div id="surveyor_sections">';
    if ($surveyor_data_old) {
        foreach($surveyor_data_old as $surveyor_data){
        if ($surveyor_data->id == $surveyorName || $surveyorName == "Null") {
            $css = "display:block;";
        } else {
            $css = "display:none;";
        }
        $html .= '<div id="surveyor_section_'.$surveyor_data->id.'" class="row form-group" style="margin-top: 10px; margin-left: 3px; '.$css.'">';
        $html .='<div class="row">';
        $html .='<div class="col-md-2" style="border: 1px solid; padding: 6px; border-right: none; align-items: center; display: flex;">';
        $html.='<div class="form-check">';
        $html .= '<input class="form-check-input surveyor-radio surveyor_radio_checked" type="radio" name="surveyor_time_zone_name" id="surveyor_time_zone_name_' . $surveyor_data->id . '" value="' . $surveyor_data->id . '" data-surveyor-id="' . $surveyor_data->id . '"';
        $surveyor_var = (!empty($survey_data) && $survey_data->name == $surveyor_data->id) ? 'checked' : '';
        $html .= ' ' . $surveyor_var . ' />';
        $html .= '<label class="form-check-label" for="surveyor_time_zone_name_' . $surveyor_data->id . '">';
        $html .= $surveyor_data->name;
        $html .= '</label>';
        $html.="</div>";
        $html.="</div>";
        $time_zone_ids = explode(',', $surveyor_data->time_zone_id);
        $html .= '<div class="row col-md-10" style="border: 1px solid; padding: 10px;">';
        foreach ($time_zone_ids as $time_zone_id) {
        $html .= '<div class="form-check col-md-2 ajax_replace" style="margin-top: 5px;">';
        $html.='<input class="form-check-input time-zone-radio surveyor-' . $surveyor_data->id . '"
        type="radio"
        name="surveyor_time_zone_' . $surveyor_data->id . '"
        id="surveyor_time_zone_' . $surveyor_data->id . '_' . $time_zone_id . '"
        value="' . $time_zone_id . '"';
        $var = (!empty($survey_data) && $survey_data->surveyor_time_zone == $time_zone_id) ? 'checked' : '';
       $var1 = (array_key_exists($surveyor_data->id, $surveyorName_new) && in_array($time_zone_id, $surveyorName_new[$surveyor_data->id]))
        ? 'disabled style="background-color: red;"'
        : '';
        $html .= ' ' . $var . ' ' . $var1 . ' />';
        $html .= '<label class="form-check-label" for="surveyor_time_zone_' . $surveyor_data->id . '_' . $time_zone_id . '">';
        $html .= Helper::time_zonename($time_zone_id);
        $html .= '</label>';
        $html.="</div>";
    }
        $html.="</div>";
        $html.="</div>";
        $html.="</div>";
    }
}
// $html .= '</div>';
    echo $html;
    // return response()->json(['time_zones' => $timeZones]);
}
    public function costing($id)
    {
        $data['inquiry_id'] = $id;
        // $followup_data= DB::table('followups')->where('id',$data['inquiry_id'])->first();
        return view('admin.add_costing_info',$data);
        // echo"<pre>";print_r($followup_data);echo"</pre>";exit;
    }
    public function costing_information(Request $request){
        // echo"<pre>";print_r($request->all());echo"</pre>";exit;
    }
    public function get_quote(Request $request)
    {
        // echo "<pre>";print_r($request->all());echo"</pre>";exit;
        $data['inquiry_id'] = $request->inquiry_id;
        $data['quote_no'] = $request->quote_no;
        $data['allow_vat'] = $request->allow_vat;
        $data['moving_type'] = $request->moving_type;
        $data['moving_value'] = $request->moving_value;
        $data['moving_date'] =date('Y-m-d', strtotime($request->moving_date));
        $data['survey_type'] = $request->survey_type;
        $data['survey_value'] = $request->survey_value;
        $data['survey_date'] =date('Y-m-d', strtotime($request->survey_date));
        if($request->surveyor !=""){
            $data['surveyor'] = $request->surveyor;
        }else{
            $data['surveyor'] = NULL;
        }
        // if($request->survey_date !=""){
        //     $data['survey_date'] = date('Y-m-d', strtotime($request->survey_date));
        // }else{
        //     $data['survey_date'] = NULL;
        // }
        $data['quotetion_date'] =  date('Y-m-d', strtotime($request->quotetion_date));
        $data['prepaid_by'] = $request->prepaid_by;
        $data['desc_of_goods'] = $request->desc_of_goods;
        if($request->input_goods !=""){
            $data['any_other_input_goods'] = $request->input_goods;
         }
        $data['service_required'] = $request->service_required;
        if($request->service_req_val !=""){
            $data['service_req_other_value'] = $request->service_req_val;
         }else{
            $data['service_req_other_value'] = " ";
         }
        $data['transport_mode'] = $request->transport_mode;
        if($request->trans_mode_val !=""){
            $data['transport_mode_other_value'] = $request->trans_mode_val;
         }
        $data['estimate_vol_in_kgs'] = $request->estimate_vol_in_kgs;
        $data['origin_add'] = $request->origin_add;
        $data['destination_add'] = $request->destination_add;
        $data['est_time'] = $request->est_time;
        $data['validity'] = $request->validity;
        $data['price_include'] = $request->price_include;
        $data['price_exclude'] = $request->price_exclude;
        $data['insurances'] = $request->insurances;
        $data['price_note'] = $request->price_note;
        $data['payment_terms'] = $request->payment_terms;
        $data['payment_options'] = $request->payment_options;
        $data['a_c_name'] = $request->a_c_name;
        $data['a_c_no'] = $request->a_c_no;
        $data['bank_add'] = $request->bank_add;
        $data['iban_no'] = $request->iban_no;
        $data['swift_code'] = $request->swift_code;
        $data['beneficiary_bane_name'] = $request->beneficiary_bane_name;
        $data['quote_form'] = 1;
        $quote_data = DB::table('inquiry_get_quote')->where('inquiry_id',$data['inquiry_id'])->first();
        if($quote_data == ''){
           $quote_id= DB::table('inquiry_get_quote')->insert($data);
           $total_amount = 0; // Initialize total amount
           if (count($_POST['particulars']) > 0 && $_POST['particulars'] != '') {
            for ($i = 0; $i < count($_POST['particulars']); $i++) {
                if($_POST['particulars'][$i] != '')
                {
                    $content['inquiry_id'] =$data['inquiry_id'];;
                    $content['particulars'] = $_POST['particulars'][$i];
                    $content['amount'] = $_POST['amount'][$i];
                     $total_amount += $_POST['amount'][$i];
                    $this->insert_attribute($content);
                }
            }
            if ($request->allow_vat) {
                $total_amount_with_vat_new = $total_amount * 5/100; // Increase total amount by 5%
                    $total_amount_with_vat = $total_amount + $total_amount_with_vat_new; // Increase total amount by 5%
            } else {
                $total_amount_with_vat = $total_amount;
            }
        }
            $followup_data = ['total_amount' => $total_amount_with_vat];
            DB::table('followups')->where('id',$data['inquiry_id'])->update($followup_data);
        }else{
             DB::table('inquiry_get_quote')->where('inquiry_id', $data['inquiry_id'])->update($data);
            if ($request->amountu != '' && count($request->amountu) > 0 ) {
                $total_amount_update = 0; // Initialize total amount
                for ($i = 0; $i < count($_POST['amountu']); $i++) {
                    if($_POST['amountu'][$i] != ''){
                        $content['inquiry_id'] = $data['inquiry_id'];
                        $content['particularsu'] = $_POST['particularsu'][$i];
                        $content['amountu'] = $_POST['amountu'][$i];
                        $content['updateid1xxx'] = $_POST['updateid1xxx'][$i];
                        $total_amount_update += $_POST['amountu'][$i];
                        $this->update_attribute($content);
                    }
                }
            }
            if (count($_POST['particulars']) > 0 && $_POST['particulars'] != '') {
                  $total_amount = 0; // Initialize total amount
                for ($i = 0; $i < count($_POST['particulars']); $i++) {
                    if($_POST['particulars'][$i] != '')
                    {
                        $content['inquiry_id'] = $data['inquiry_id'];
                        $content['particulars'] = $_POST['particulars'][$i];
                        $content['amount'] = $_POST['amount'][$i];
                        $total_amount += $_POST['amount'][$i];
                        $this->insert_attribute($content);
                    }
                }
            }
            $total_aaa =$total_amount_update + $total_amount;
            if ($request->allow_vat) {
                $total_amount_with_vat_new =  $total_aaa * 5/100;
                $final_aa = $total_aaa + $total_amount_with_vat_new;
            } else {
                $final_aa = $total_aaa;
            }
            $followup_data_update = ['total_amount' => $final_aa
                // Add other fields as necessary
            ];
            DB::table('followups')->where('id',$data['inquiry_id'])->update($followup_data_update);
        }
        return  redirect()->route('followup.index')->with('success', 'Get Quotes Added successfully');
    }
    function insert_attribute($content)
    {
        $data['inquiry_id'] = $content['inquiry_id'];
        $data['particulars'] = $content['particulars'];
        $data['amount'] = $content['amount'];
        DB::table('get_quote_attribute')->insertGetId($data);
    }
    function update_attribute($content){
        $data['inquiry_id'] = $content['inquiry_id'];
        $data['particulars'] = $content['particularsu'];
        $data['amount'] = $content['amountu'];
        DB::table('get_quote_attribute')->where('id', $content['updateid1xxx'])->update($data);
    }
    public function remove_quote_att (Request $request){
        $inquiry_id = $request->inquiry_id;
        $id = $request->id;
        $result = DB::table('get_quote_attribute')->where('inquiry_id', '=',$inquiry_id)->where('id', '=',$id)->delete();
        return redirect()->route('followup.get_quote_form', ['id' => $inquiry_id])->with('success', 'Get Quotes Attributes Deleted successfully');
    }
    function quote_accept_form(Request $request){
        $id = $request->inquiry_id;
        $intOrderNumber = DB::table('followups')
                             ->select(DB::raw('MAX(job_id) as lastjobNumber'))
                             ->first();
                         $job_no =  $intOrderNumber->lastjobNumber + 1;
        $data['user_data_new'] = DB::table('followups')->where('id', $id)->first();
        $data['get_quote_attribute'] = DB::table('get_quote_attribute')->where('inquiry_id', $id)->get();
        $data['inquiry_get_quote'] = DB::table('inquiry_get_quote')->where('inquiry_id', $id)->get();
        $salesmanData = DB::table('users')->where('id', $data['user_data_new']->salesman_id)->first();
        // echo"<pre>";print_r( $data['user_data_new']);echo"</pre>";exit;
        $update_data['get_quote_accept_reject'] = 1;
        $subject = "Thank you";
        $user_mail = $data['user_data_new']->customer_email;
        $pdf = PDF::loadView('admin.mailpdf', $data);
        $admin = 'zafar@quickserverelo.com';
        $salesmanemail = $salesmanData->email;
        if (isset($user_mail) && filter_var($user_mail, FILTER_VALIDATE_EMAIL)) {
            $to = [$user_mail];
            Mail::send([], [], function ($message) use ($pdf, $to, $subject) {
                $message->to($to);
                $message->subject($subject);
                $message->from('devang.hnrtechnologies@gmail.com', 'ERP');
                $message->attachData($pdf->output(), "mailpdf.pdf");
            });
        }
        Mail::send([], [], function ($message) use ($pdf, $admin, $subject) {
            $message->to($admin);
            $message->subject($subject);
            $message->from('devang.hnrtechnologies@gmail.com', 'ERP');
            $message->attachData($pdf->output(), "mailpdf.pdf");
        });
        if (isset($salesmanemail) && filter_var($salesmanemail, FILTER_VALIDATE_EMAIL)) {
            $to = [$salesmanemail];
            Mail::send([], [], function ($message) use ($pdf, $to, $subject) {
                $message->to($to);
                $message->subject($subject);
                $message->from('devang.hnrtechnologies@gmail.com', 'ERP');
                $message->attachData($pdf->output(), "mailpdf.pdf");
            });
        }
        DB::table('inquiry_get_quote')->where('inquiry_id',$id)->update($update_data);
        $update_data_inquiry['accept_reject'] = 1;
        $update_data_inquiry['job_id'] = $job_no;
        DB::table('followups')->where('id',$id)->update($update_data_inquiry);
        return  redirect()->route('followup.index')->with('success', 'Accept successfully');
    }
    function quote_reject_form(Request $request){
        $inquiry_id = $_POST['inquiry_id'];
        $update_data['reject_reason'] = $_POST['reject_reason'];
        //     echo"<pre>";
        //     print_r($update_data['reject_reason']);
        // echo"</pre>";exit;
        $update_data['get_quote_accept_reject'] = 2;
        $user_data_new=DB::table('followups')->where('id',$inquiry_id)->first();
        $data['get_quote_attribute'] = DB::table('get_quote_attribute')->where('inquiry_id', $inquiry_id)->get();
            $update_data['get_quote_accept_reject'] = 2;
            $pdf = PDF::loadView('admin.mailpdf', $data);
            //return $pdf->download($message_body);
             $subject = "Thank you";
        $user_mail = $user_data_new->customer_email;
        $to = [$user_mail];
        // $to = $request->email;
        //      echo"<pre>";
        //     print_r($message_body);
        // echo"</pre>";exit;
        Mail::send([], [], function($message) use($pdf, $to, $subject) {
            $message->to($to);
            $message->subject($subject);
            $message->from('devang.hnrtechnologies@gmail.com', 'ERP');
            // $message->html($message_body);
            $message->attachData($pdf->output(), "mailpdf.pdf");
        });
        DB::table('inquiry_get_quote')->where('inquiry_id',$inquiry_id)->update($update_data);
        $update_data_inquiry['accept_reject'] = 2;
        DB::table('followups')->where('id',$inquiry_id)->update($update_data_inquiry);
        return  redirect()->route('followup.index')->with('success', 'Reject successfully');
    }
    function test_pdf_html($id){
    //    echo $id = 4;exit;
        $data['quotes_data'] = DB::table('inquiry_get_quote')
        ->where('inquiry_id', $id)
        ->first();
        $data['quote_attr_data'] = DB::table('get_quote_attribute')
        ->where('inquiry_id', $id)
        ->get();
        $data['followup_data'] = DB::table('followups')
        ->where('id', $id)
        ->first();
        $quote_no = $data['quotes_data']->quote_no;
        $pdfFileName = $quote_no.'.pdf';
        return view('admin.get_quote_pdf',$data);
    }
    public function get_quote_pdf($id)
    {
        $data['quotes_data'] = DB::table('inquiry_get_quote')
        ->where('inquiry_id', $id)
        ->first();
        $data['quote_attr_data'] = DB::table('get_quote_attribute')
        ->where('inquiry_id', $id)
        ->get();
        $data['followup_data'] = DB::table('followups')
        ->where('id', $id)
        ->first();
        $quote_no = $data['quotes_data']->quote_no;
        $pdfFileName = $quote_no.'.pdf';
        // echo"<pre>";
        //     print_r($data['quotes_data']);
        // echo"</pre>";exit;
        if($quote_no !=''){
            $pdf = PDF::loadView('admin.get_quote_pdf', $data);
            return $pdf->download($pdfFileName);
        }
    //    $pdf = PDF::loadView('admin.get_quote_pdf', $data);
    //    return $pdf->download($pdfFileName);
        // $html = view('admin.get_quote_pdf', $data)->render();
        // $pdf = PDF::loadHTML($html);
        // $pdf = PDF::loadView('pdf.document', $data);
        // return $pdf->stream($pdfFileName);
        // $html = view('admin.get_quote_pdf', $data)->render();
       // echo $html;exit;
        // Load HTML content
        // $dompdf->loadHtml($html);
        // $dompdf->setPaper('A4', 'portrait');
        // (Optional) Setup the paper size and orientation
        // $dompdf->setPaper('A4', 'landscape');
        // $dompdf->render();
        // Output the generated PDF and force download
        // $dompdf->stream($pdfFileName);
}
    public function repeated_inq($id){
        $data['sourcelead_data']= Source_lead::orderBy('id','DESC')->get();
        $data['service_data']= Service::orderBy('id','DESC')->get();
        $data['salesman_data'] = DB::table('users')->Where('id','!=', 1)->get();
        $data['followup'] = DB::table('followups')
        ->where('id', $id)
        ->first();
        // echo"<pre>";print_r($data['followup']);echo"</pre>";exit;
        return view('admin.repeted_inq',$data);
}
public function add_repeated_inq(Request $request)
{
    // echo"<pre>";print_r($request->all());echo"</pre>";
    $data['quote_no']= $request->quote_no;
    $data['sourcelead_id']= $request->sourcelead_id;
    $data['service_id']= $request->service_id;
    $data['salesman_id']= $request->salesman_id;
    $data['inquiry_type'] = $request->inquiry_type;
    $data['inquiry_value'] = $request->inquiry_value;
    $data['inquiry_date'] =date('Y-m-d', strtotime($request->inquiry_date));
    $data['move_type'] = $request->move_type;
    $data['move_value'] = $request->move_value;
    $data['move_date'] =date('Y-m-d', strtotime($request->move_date));
    $data['volume']= $request->volume ;
    $data['company_name_id']= $request->company_name_id;
    $data['company_name']= $request->company_name;
    $data['customer_name']= $request->customer_name;
    $data['customer_email']= $request->customer_email;
    $data['customer_phone1']= $request->customer_phone1;
    $data['customer_phone2']= $request->customer_phone2;
    $data['origin']= $request->origin;
    $data['destination']= $request->destination;
    $data['address']= $request->address;
    $data['added_date']= date('Y-m-d');
    $id = DB::table('followups')->insertGetId($data);
    if($id !=""){
        $datau['quote_no']      = 'QSR-'.$id;
        DB::table('followups')->where('id',$id)->update($datau);
    }
    return  redirect()->route('followup.index')->with('success', 'Inquiry  added successfully');
}
function agent_att_data() {
    $id = $_POST['id'];
    $data = DB::table('agents_attribute')->where('agent_id', $id)
             ->orderBy('id', 'desc')->get();
    $html = "<select class='form-control' id='agent_att_id' name='agent_att_id' onchange=\"agent_att_data_replace(this.value);\">";
    $html .= "<option value=''>Select Agent Attribute</option>";
    if ($data != '' && count($data) > 0) {
        for ($i = 0; $i < count($data); $i++) {
            $html .= "<option value='" . $data[$i]->id . "'>Name-" . $data[$i]->name . ", Position-" . $data[$i]->role . "</option>";
        }
    }
    $html .= "</select>";
    echo $html;
}
function agent_data_assign() {
    $id = $_POST['id'];
    // Retrieve the data from the database
    $att_data = DB::table('agents_attribute')
                    ->where('id', $id)
                    ->orderBy('id', 'desc')
                    ->first();
    // Check if data exists
    if ($att_data) {
        // Return the data as JSON
        $response = [
            'name' => $att_data->name,
            'email' => $att_data->email, // Add email field
            'phone' => $att_data->telephone // Add phone field
        ];
        echo json_encode($response);
    } else {
        // Handle the case where no data is found
        echo "Data not found!";
    }
}
}
