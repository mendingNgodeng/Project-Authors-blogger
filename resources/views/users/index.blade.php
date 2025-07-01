@extends('layouts.sidebar')
<x-layouts.sidebar>
    <x-slot name="header">
        My Page Title
    </x-slot>

    <!-- Your table and form go here -->
    <div class="mb-6">
        <!-- Table placeholder -->
        <table class="min-w-full bg-white shadow rounded">
            <thead>
                <tr>
                    <th class="px-6 py-3 border-b">Column 1</th>
                    <th class="px-6 py-3 border-b">Column 2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-6 py-4 border-b">Row 1</td>
                    <td class="px-6 py-4 border-b">Row 2</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div>
        <!-- Form placeholder -->
        <form action="#" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-1">Name</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
        </form>
    </div>
</x-layouts.sidebar>