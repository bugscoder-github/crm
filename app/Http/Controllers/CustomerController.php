<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller {
	public function index() {
		$customers = Customer::all();
		return Inertia::render('Customer/Index', [
			'customers' => $customers
		]);
	}

	public function create() {
		return Inertia::render('Customer/Form', ['customer' => new Customer()]);
	}

	public function edit(Customer $customer) {
		return Inertia::render('Customer/Form', [
			'customer' => $customer,
			'success' => session('success') ?? '',
		]);
	}

	///

	public function store(CustomerRequest $request) {
		$result = $this->save($request);

		return redirect()->route('customer.edit', $result->customer_id)->withInput()->with('success', 'Customer created successfully.');
	}

	public function update(CustomerRequest $request, Customer $customer) {
		$result = $this->save($request, $customer);

		return redirect()->route('customer.edit', $result->customer_id)->withInput()->with('success', 'Customer updated successfully.');
	}

	public function save(CustomerRequest $request, Customer $customer = null) {
		$data = $request->validated();

		if ($customer) {
			$customer->update([
				'customer_name' => $data['customer_name'],
				'customer_phone' => $data['customer_phone'],
			]);
		} else {
			$customer = Customer::create([
				'customer_name' => $data['customer_name'],
				'customer_phone' => $data['customer_phone'],
			]);
		}

		return $customer;
	}

	///

	public function destroy(Customer $customer) {
		$customer->delete();
		return redirect()->route('customers.index')->with('success', 'Customer deactivated successfully.');
	}


    public function show(Customer $customer) {
    	return $customer;
    }

	public function search(Request $request) {
        $query = $request->input('query');
		
		$result = Customer::where('customer_name', 'like', '%'.$query.'%')
					->orWhere('customer_phone','like', '%'.$query.'%')->get();
		return $result;
	}
}
