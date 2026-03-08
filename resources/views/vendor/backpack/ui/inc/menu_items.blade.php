{{-- This file is used for menu items by any Backpack v7 theme --}}
<x-backpack::menu-separator title="Core" />
<x-backpack::menu-item title="Dashboard" icon="la la-home" :link="backpack_url('dashboard')" />
{{-- <x-backpack::menu-item title="Users" icon="la la-users" :link="backpack_url('user')" /> --}}

<x-backpack::menu-separator title="Academics" />
<x-backpack::menu-dropdown title="Student Management" icon="la la-user-graduate">
    <x-backpack::menu-dropdown-item title="Students" icon="la la-user" :link="backpack_url('student')" />
    <x-backpack::menu-dropdown-item title="Teachers" icon="la la-chalkboard-teacher" :link="backpack_url('teacher')" />
    <x-backpack::menu-dropdown-item title="Classrooms" icon="la la-school" :link="backpack_url('classroom')" />
    <x-backpack::menu-dropdown-item title="Classroom Teachers" icon="la la-link" :link="backpack_url('classroom-teacher')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-dropdown title="Academic Records" icon="la la-book-open">
    <x-backpack::menu-dropdown-item title="Attendance" icon="la la-calendar-check" :link="backpack_url('attendance')" />
    <x-backpack::menu-dropdown-item title="Grades" icon="la la-chart-line" :link="backpack_url('grade')" />
    <x-backpack::menu-dropdown-item title="Reports" icon="la la-file-alt" :link="backpack_url('report')" />
</x-backpack::menu-dropdown>

<x-backpack::menu-separator title="Operations" />
<x-backpack::menu-item title="Fee Payments" icon="la la-money-bill" :link="backpack_url('fee-payment')" />
{{-- <x-backpack::menu-item title="Tasks" icon="la la-tasks" :link="backpack_url('task')" /> --}}
