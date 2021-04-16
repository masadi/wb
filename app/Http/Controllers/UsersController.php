<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\MatchOldPassword;
use Carbon\Carbon;
use File;
use Image;
class UsersController extends Controller
{
	public function __construct()
    {
        $this->path = public_path('images');
		$this->dimensions = ['245', '300', '500'];
    }
    public function index() {
        $users = User::where(function($query){
            $query->where('id', '<>', request()->user_id);
        })->orderBy(request()->sortby, request()->sortbydesc)
            //JIKA Q ATAU PARAMETER PENCARIAN INI TIDAK KOSONG
            ->when(request()->q, function($posts) {
                //MAKA FUNGSI FILTER AKAN DIJALANKAN
                $posts = $posts->where('name', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('email', 'LIKE', '%' . request()->q . '%')
                    ->orWhere('username', 'LIKE', '%' . request()->q . '%');
        })->paginate(request()->per_page); //KEMUDIAN LOAD PAGINATIONNYA BERDASARKAN LOAD PER_PAGE YANG DIINGINKAN OLEH USER
        return response()->json(['status' => 'success', 'data' => $users]);
    }

    public function create(Request $request)
    {

         Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

	}
	public function store(Request $request){
		$messages = [
			'name.required'	=> 'Nama Lengkap tidak boleh kosong',
			'email.email'	=> 'Email Tidak Valid',
			'email.unique'	=> 'Email sudah terdaftar di database',
			//'current_password.nullable' => 'Please enter current password',
    		'password.nullable' => 'Please enter password',
			'email.required'	=> 'Email tidak boleh kosong',
			'password.required_with_all' => 'Password baru tidak boleh kosong',
			'password.confirmed' => 'Konfirmasi password tidak sama',
			'password.min' => 'Password minimal 8 karakter',
			//'token.required' => 'Token tidak boleh kosong',
			//'token.unique' => 'Token sudah terdaftar di database',
		];
		$validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
			//'token' => ['required', 'string', 'max:255', 'unique:users,username'],
        ],
		$messages
		)->validate();
		//$username = strtolower($request->token);
		//$username = str_replace(' ', '', $username);
		//$username = trim($username);
        $user =  User::create([
            'name' => $request['name'],
			'email' => $request['email'],
			'type' => $request->type['key'],
			'password' => Hash::make($request->password),
			//'token' => strtolower($request->token),
		]);
		if(!$user->hasRole($request->type['key'])){
			$role = Role::where('name', $request->type['key'])->first();
			if(!$role){
				$role = Role::create(['name' => $request->type['key'], 'display_name' => $request->type['value']]);
			}
			$user->attachRole($role);
		}
		$response = [
			'title' => 'Berhasil',
			'text' => 'Pengguna berhasil ditambahkan',
			'icon' => 'success',
		];
		return response()->json($response);
	}
    public function profile(Request $request){
        $data = User::find($request->user_id);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function update_profile(Request $request){
		$id = $request->user_id;
		$messages = [
			'image.mimes'	=> 'Foto profile harus berekstensi jpg/png/jpeg',
			'image.image'	=> 'Foto profile harus berekstensi jpg/png/jpeg',
			/*'current_password.nullable' => 'Please enter current password',
    		'password.nullable' => 'Please enter password',
			'email.required'	=> 'Email tidak boleh kosong',
			'password.required_with_all' => 'Password baru tidak boleh kosong',
			'password_confirmation.same' => 'Konfirmasi sandi tidak sama dengan sandi baru',*/
			'password.min' => 'Password minimal terdiri dari 8 karakter',
			'password_confirmation.confirmed' => 'Konfirmasi Password salah',
			'token.required' => 'Token tidak boleh kosong',
			'token.unique' => 'Token sudah terdaftar di database',
		];
		$validator = Validator::make(request()->all(), [
			'image'					=> 'nullable|image|mimes:jpg,png,jpeg',
			'name'					=> 'required',
			'token'					=> 'required|unique:users,token,' . $id .',user_id',
            'email'					=> 'required|email|unique:users,email,' . $id .',user_id',
			//'current_password'		=> 'nullable',
			'current_password'		=> ['nullable', new MatchOldPassword($request->user_id)],
			'password'				=> 'nullable|required_with_all:current_password,email|min:8,confirmed',
			//'password_confirmation'	=> 'confirmed',
		],
		$messages
		)->validate();
		//JIKA FOLDERNYA BELUM ADA
        if (!File::isDirectory($this->path)) {
            //MAKA FOLDER TERSEBUT AKAN DIBUAT
            File::makeDirectory($this->path, 0777, true, true);
		}
		$path = public_path('images');
		//MENGAMBIL FILE IMAGE DARI FORM
        $file = $request->file('image');
		//$current_password_post = $request->current_password;
		$user = User::findOrFail($id);
		if($request->current_password){
			if(Hash::check($request->current_password, $user->password)){       
				$user->password = Hash::make($request->password);
			}
		}
		if($file){
			$image_path = public_path("images/".$user->photo);
			if(File::exists($image_path)) {
				File::delete($image_path);
			}
			//MEMBUAT NAME FILE DARI GABUNGAN TIMESTAMP DAN UNIQID()
			$fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
			//UPLOAD ORIGINAN FILE (BELUM DIUBAH DIMENSINYA)
			Image::make($file)->save($this->path . '/' . $fileName);
			
			//LOOPING ARRAY DIMENSI YANG DI-INGINKAN
			//YANG TELAH DIDEFINISIKAN PADA CONSTRUCTOR
			foreach ($this->dimensions as $row) {
				$image_dimensions = public_path("images/".$row.'/'.$user->photo);
				if(File::exists($image_dimensions)) {
					File::delete($image_dimensions);
				}
				//MEMBUAT CANVAS IMAGE SEBESAR DIMENSI YANG ADA DI DALAM ARRAY 
				$canvas = Image::canvas($row, $row);
				//RESIZE IMAGE SESUAI DIMENSI YANG ADA DIDALAM ARRAY 
				//DENGAN MEMPERTAHANKAN RATIO
				$resizeImage  = Image::make($file)->resize($row, $row, function($constraint) {
					$constraint->aspectRatio();
				});
				
				//CEK JIKA FOLDERNYA BELUM ADA
				//if (!File::isDirectory($this->path . '/' . $row)) {
					//MAKA BUAT FOLDER DENGAN NAMA DIMENSI
					//File::makeDirectory($this->path . '/' . $row);
				//}
				$row_path = public_path('images/'.$row);
				if(!File::isDirectory($row_path)){
					File::makeDirectory($row_path, 0777, true, true);
				}
				//MEMASUKAN IMAGE YANG TELAH DIRESIZE KE DALAM CANVAS
				$canvas->insert($resizeImage, 'center');
				//SIMPAN IMAGE KE DALAM MASING-MASING FOLDER (DIMENSI)
				$canvas->save($this->path . '/' . $row . '/' . $fileName);
			}
			$user->photo = $fileName;
		}
		$user->nuptk = $request->nuptk;
		$user->nip = $request->nip;
		$user->asal_institusi = $request->asal_institusi;
		$user->alamat_institusi = $request->alamat_institusi;
		$user->nomor_hp = $request->nomor_hp;
		$user->token = trim(strtolower($request->token));
		$user->name = $request->input('name');
        $user->email = strtolower($request->input('email'));
		if($user->save()){
			$with = 'success';
			$text = 'Profile pengguna berhasil diperbaharui.';
		} else {
			$with = 'error';
			$text = 'Profile pengguna gagal diperbaharui. Periksa kembali isian Anda';
		}
		if($user->hasRole('siswa')){
			$siswa = $user->siswa;
			if($siswa){
				$siswa->email = strtolower($request->input('email'));
				$siswa->save();
			}
		}
		return response()->json(['status' => 'success', 'message' => 'Profile pengguna berhasil diperbaharui', 'data' => $user]);
	}
	public function reset_passsword(Request $request){
		$user = User::find($request->user_id);
		$user->password = Hash::make($user->username);
		if($user->save()){
			$response = [
				'title' => 'Berhasil',
				'text' => 'Password pengguna berhasil direset',
				'icon' => 'success',
			];
		} else {
			$response = [
				'title' => 'Gagal',
				'text' => 'Password pengguna gagal direset',
				'icon' => 'error',
			];
		}
		return response()->json($response);
	}
	public function destroy($id){
		$user = User::find($id);
		$user->delete();
		$response = [
			'title' => 'Berhasil',
			'text' => 'Pengguna berhasil dihapus',
			'icon' => 'success',
		];
		return response()->json($response);
	}
	public function update(Request $request, $id){
		$messages = [
			'name.required'	=> 'Nama Lengkap tidak boleh kosong',
			'email.required'	=> 'Email tidak boleh kosong',
			'token.required' => 'Token tidak boleh kosong',
			'token.unique' => 'Token sudah terdaftar di database',
		];
		$validator = Validator::make(request()->all(), [
			'name'					=> 'required',
			'token'					=> 'required|unique:users,token,' . $id .',user_id',
            'email'					=> 'required|email|unique:users,email,' . $id .',user_id',
		],
		$messages
		)->validate();
		$user = User::find($id);
		$user->name = $request->name;
		$user->nomor_hp = $request->nomor_hp;
		$user->email = $request->email;
		$user->token = strtolower($request->token);
		$user->nip = $request->nip;
		if($user->save()){
			$response = [
				'title' => 'Berhasil',
				'text' => 'Data Verifikator berhasil diperbaharui',
				'icon' => 'success',
			];
		} else {
			$response = [
				'title' => 'Gagal',
				'text' => 'Data Verifikator gagal diperbaharui',
				'icon' => 'error',
			];
		}
		return response()->json($response);
	}
}
