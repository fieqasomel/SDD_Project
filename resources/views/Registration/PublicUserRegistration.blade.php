<!-- resources/views/Registration/PublicUserRegistration.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public User Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-800 flex items-center justify-center p-6">
    <div class="bg-white p-8 rounded-2xl shadow-xl max-w-3xl w-full">
        <h2 class="text-3xl font-bold mb-6 text-center text-gray-800">Public User Registration</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('publicuser.register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Full Name</label>
                    <input type="text" name="PU_Name" value="{{ old('PU_Name') }}" class="w-full border border-gray-300 rounded p-2" required>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">IC Number</label>
                    <input type="text" name="PU_IC" value="{{ old('PU_IC') }}" class="w-full border border-gray-300 rounded p-2" required>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Age</label>
                    <input type="number" name="PU_Age" value="{{ old('PU_Age') }}" class="w-full border border-gray-300 rounded p-2" required>
                </div>

                <div>
                    <label class="block mb-1 font-semibold text-gray-700">Phone Number</label>
                    <input type="text" name="PU_PhoneNum" value="{{ old('PU_PhoneNum') }}" class="w-full border border-gray-300 rounded p-2" required>
                </div>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Address</label>
                <textarea name="PU_Address" class="w-full border border-gray-300 rounded p-2" required>{{ old('PU_Address') }}</textarea>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Email Address</label>
                <input type="email" name="PU_Email" value="{{ old('PU_Email') }}" class="w-full border border-gray-300 rounded p-2" required>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Gender</label>
                <select name="PU_Gender" class="w-full border border-gray-300 rounded p-2" required>
                    <option value="" disabled {{ old('PU_Gender') ? '' : 'selected' }}>Select gender</option>
                    <option value="Male" {{ old('PU_Gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('PU_Gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Profile Picture</label>
                <input type="file" name="PU_ProfilePicture" class="w-full border border-gray-300 rounded p-2">
            </div>

            <div>
                <label class="block mb-1 font-semibold text-gray-700">Password</label>
                <input type="password" name="PU_Password" class="w-full border border-gray-300 rounded p-2" required>
            </div>

            <div class="text-center">
                <button type="submit" class="bg-purple-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-purple-700 transition">Register</button>
            </div>
        </form>
    </div>
</body>
</html>
