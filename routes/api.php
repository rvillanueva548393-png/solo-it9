use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

// Endpoint: [http://127.0.0.1:8000/api/employees](http://127.0.0.1:8000/api/employees)
Route::get('/employees', [EmployeeController::class, 'index']);

// Endpoint: [http://127.0.0.1:8000/api/employees/register](http://127.0.0.1:8000/api/employees/register)
Route::post('/employees/register', [EmployeeController::class, 'store']);
