@php
    $url = '';

    $MyNavBar = \Menu::make('MenuList', function ($menu) use($url){
        $unreadCount = auth()->user()->unreadNotifications->count();

// Determine whether to show the badge based on the unread count
// The badge is always rendered, but its visibility is toggled based on the unread count.
$badge = "<span class='badge bg-info' id='unread-badge' style='display: " . ($unreadCount > 0 ? "inline" : "none") . ";'>{$unreadCount}</span>";


        //Admin Dashboard
        $menu->add('<span class="item-name">'.__('message.dashboard').'</span>', ['route' => 'dashboard'])
            ->prepend('<i class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.5 15.5C13.5 13.6144 13.5 12.6716 14.0858 12.0858C14.6716 11.5 15.6144 11.5 17.5 11.5C19.3856 11.5 20.3284 11.5 20.9142 12.0858C21.5 12.6716 21.5 13.6144 21.5 15.5V17.5C21.5 19.3856 21.5 20.3284 20.9142 20.9142C20.3284 21.5 19.3856 21.5 17.5 21.5C15.6144 21.5 14.6716 21.5 14.0858 20.9142C13.5 20.3284 13.5 19.3856 13.5 17.5V15.5Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M2 8.5C2 10.3856 2 11.3284 2.58579 11.9142C3.17157 12.5 4.11438 12.5 6 12.5C7.88562 12.5 8.82843 12.5 9.41421 11.9142C10 11.3284 10 10.3856 10 8.5V6.5C10 4.61438 10 3.67157 9.41421 3.08579C8.82843 2.5 7.88562 2.5 6 2.5C4.11438 2.5 3.17157 2.5 2.58579 3.08579C2 3.67157 2 4.61438 2 6.5V8.5Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M13.5 5.5C13.5 4.56812 13.5 4.10218 13.6522 3.73463C13.8552 3.24458 14.2446 2.85523 14.7346 2.65224C15.1022 2.5 15.5681 2.5 16.5 2.5H18.5C19.4319 2.5 19.8978 2.5 20.2654 2.65224C20.7554 2.85523 21.1448 3.24458 21.3478 3.73463C21.5 4.10218 21.5 4.56812 21.5 5.5C21.5 6.43188 21.5 6.89782 21.3478 7.26537C21.1448 7.75542 20.7554 8.14477 20.2654 8.34776C19.8978 8.5 19.4319 8.5 18.5 8.5H16.5C15.5681 8.5 15.1022 8.5 14.7346 8.34776C14.2446 8.14477 13.8552 7.75542 13.6522 7.26537C13.5 6.89782 13.5 6.43188 13.5 5.5Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M2 18.5C2 19.4319 2 19.8978 2.15224 20.2654C2.35523 20.7554 2.74458 21.1448 3.23463 21.3478C3.60218 21.5 4.06812 21.5 5 21.5H7C7.93188 21.5 8.39782 21.5 8.76537 21.3478C9.25542 21.1448 9.64477 20.7554 9.84776 20.2654C10 19.8978 10 19.4319 10 18.5C10 17.5681 10 17.1022 9.84776 16.7346C9.64477 16.2446 9.25542 15.8552 8.76537 15.6522C8.39782 15.5 7.93188 15.5 7 15.5H5C4.06812 15.5 3.60218 15.5 3.23463 15.6522C2.74458 15.8552 2.35523 16.2446 2.15224 16.7346C2 17.1022 2 17.5681 2 18.5Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg></i>')
            ->link->attr([ 'class' => activeRoute(route('dashboard')) ? 'nav-link active' : 'nav-link' ]);

        $menu->add('<span class="item-name">'.__('message.user').'</span>', ['class' => ''])
            ->prepend('<i class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="9" cy="6" r="4" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M15 9C16.6569 9 18 7.65685 18 6C18 4.34315 16.6569 3 15 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        <ellipse cx="9" cy="17" rx="7" ry="4" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M18 14C19.7542 14.3847 21 15.3589 21 16.5C21 17.5293 19.9863 18.4229 18.5 18.8704" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg></i>')
            ->nickname('user')
            ->data('permission', 'user-list')
            ->link->attr(['class' => 'nav-link' ])
            ->href('#user');

            $menu->user->add('<span class="item-name">'.__('message.list_form_title',['form' => __('message.user')]).'</span>', ['route' => 'users.index'])
                ->data('permission', 'user-list')
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('users.index')) ? 'nav-link active' : 'nav-link']);

            $menu->user->add('<span class="item-name">'.__('message.add_form_title',['form' => __('message.user')]).'</span>', ['route' => 'users.create'])
                ->data('permission', [ 'user-add', 'user-edit'])
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('users.create')) || request()->is('users/*/edit') ? 'nav-link active' : 'nav-link']);

                    $menu->add('<span class="item-name">'.__('message.bodypart').'</span>', ['class' => ''])
                ->prepend('<i class="icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.38892 13.1614C8.22254 12.0779 12.9999 11.0891 16.6405 14.8096" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M2 10.5588C5.1153 10.2051 6.39428 10.6706 8.75105 12.2516M15.5021 13.7518L13.8893 6.66258C13.8186 6.35178 13.4791 6.18556 13.1902 6.32034L10.3819 7.6308C10.1384 7.74442 9.85788 7.75857 9.61117 7.65213C8.87435 7.33425 8.38405 6.97152 7.86685 6.31394C7.61986 5.99992 7.54201 5.5868 7.62818 5.19668C7.87265 4.0899 8.12814 3.34462 8.62323 2.31821C8.70119 2.15659 8.86221 2.05093 9.04141 2.04171C11.0466 1.93856 12.3251 2.01028 14.2625 2.44371C14.5804 2.51485 14.8662 2.69558 15.0722 2.948C19.8635 8.8193 21.3943 11.9968 21.9534 16.6216C21.9872 16.9004 21.8964 17.1818 21.7073 17.3895C17.6861 21.8064 14.7759 22.3704 8.75105 20.0604C6.65624 21.5587 5.07425 21.8624 2.25004 21.3106" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg></i>')
                ->nickname('bodypart')
                ->data('permission', 'bodyparts-list')
                ->link->attr(['class' => 'nav-link' ])
                ->href('#bodypart');

            $menu->bodypart->add('<span class="item-name">'.__('message.list_form_title',['form' => __('message.bodypart')]).'</span>', ['route' => 'bodypart.index'])
                ->data('permission', 'bodyparts-list')
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('bodypart.index')) ? 'nav-link active' : 'nav-link']);

            $menu->bodypart->add('<span class="item-name">'.__('message.add_form_title',['form' => __('message.bodypart')]).'</span>', ['route' => 'bodypart.create'])
                ->data('permission', [ 'bodyparts-add', 'bodypart-edit'])
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('bodypart.create')) || request()->is('bodypart/*/edit') ? 'nav-link active' : 'nav-link']);


        $menu->add('<span class="item-name">'.__('message.equipment').'</span>', ['class' => ''])
            ->prepend('<i class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.32031 12.1982L12.2003 8.31823M15.3043 11.4222L11.4243 15.3023" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M3.43157 15.6193C2.52737 14.7151 2.07528 14.263 2.0108 13.7109C1.9964 13.5877 1.9964 13.4632 2.0108 13.3399C2.07528 12.7879 2.52737 12.3358 3.43156 11.4316C4.33575 10.5274 4.78785 10.0753 5.33994 10.0108C5.46318 9.9964 5.58768 9.9964 5.71092 10.0108C6.26301 10.0753 6.71511 10.5274 7.6193 11.4316L12.5684 16.3807C13.4726 17.2849 13.9247 17.737 13.9892 18.2891C14.0036 18.4123 14.0036 18.5368 13.9892 18.6601C13.9247 19.2122 13.4726 19.6642 12.5684 20.5684C11.6642 21.4726 11.2122 21.9247 10.6601 21.9892C10.5368 22.0036 10.4123 22.0036 10.2891 21.9892C9.73699 21.9247 9.28489 21.4726 8.3807 20.5684L3.43157 15.6193Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M11.4316 7.6193C10.5274 6.71511 10.0753 6.26301 10.0108 5.71092C9.9964 5.58768 9.9964 5.46318 10.0108 5.33994C10.0753 4.78785 10.5274 4.33576 11.4316 3.43156C12.3358 2.52737 12.7879 2.07528 13.3399 2.0108C13.4632 1.9964 13.5877 1.9964 13.7109 2.0108C14.263 2.07528 14.7151 2.52737 15.6193 3.43156L20.5684 8.3807C21.4726 9.28489 21.9247 9.73699 21.9892 10.2891C22.0036 10.4123 22.0036 10.5368 21.9892 10.6601C21.9247 11.2122 21.4726 11.6642 20.5684 12.5684C19.6642 13.4726 19.2122 13.9247 18.6601 13.9892C18.5368 14.0036 18.4123 14.0036 18.2891 13.9892C17.737 13.9247 17.2849 13.4726 16.3807 12.5684L11.4316 7.6193Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M18.0195 2.49805L21.1235 5.60206" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2.49609 18.0181L5.6001 21.1221" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg></i>')
            ->nickname('equipment')
            ->data('permission', 'equipment-list')
            ->link->attr(['class' => 'nav-link' ])
            ->href('#equipment');

            $menu->equipment->add('<span class="item-name">'.__('message.list_form_title',['form' => __('message.equipment')]).'</span>', ['route' => 'equipment.index'])
                ->data('permission', 'equipment-list')
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('equipment.index')) ? 'nav-link active' : 'nav-link']);

            $menu->equipment->add('<span class="item-name">'.__('message.add_form_title',['form' => __('message.equipment')]).'</span>', ['route' => 'equipment.create'])
                ->data('permission', [ 'equipment-add', 'equipment-edit'])
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('equipment.create')) || request()->is('equipment/*/edit') ? 'nav-link active' : 'nav-link']);

        $menu->add('<span class="item-name">'.__('message.exercise').'</span>', ['class' => ''])
            ->prepend('<i class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14.1009 2.64567C14.1009 3.69258 13.2522 4.54134 12.2051 4.54134C11.1581 4.54134 10.3093 3.69258 10.3093 2.64567C10.3093 1.59876 11.1581 0.75 12.2051 0.75C13.2522 0.75 14.1009 1.59876 14.1009 2.64567Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M1.99902 8.50402V7.00612C1.99902 6.58326 2.3459 6.24263 2.76869 6.25032L12.3933 6.4253L22.2071 6.25007C22.6298 6.24252 22.9765 6.58311 22.9765 7.00587V8.50402C22.9765 8.92151 22.6381 9.25995 22.2206 9.25995H16.4162C15.9408 9.25995 15.5834 9.69361 15.6742 10.1603L17.9978 22.0998C18.0886 22.5664 17.7312 23.0001 17.2558 23.0001H16.2843C15.9478 23.0001 15.6518 22.7777 15.5582 22.4544L13.6852 15.9867C13.5916 15.6635 13.2956 15.4411 12.9591 15.4411H11.8413C11.4983 15.4411 11.1983 15.672 11.1105 16.0036L9.40821 22.4375C9.32047 22.7691 9.02045 23.0001 8.67743 23.0001H7.70562C7.23489 23.0001 6.87864 22.5745 6.96149 22.1111L9.10041 10.1489C9.18326 9.68555 8.82701 9.25995 8.35628 9.25995H2.75495C2.33746 9.25995 1.99902 8.92151 1.99902 8.50402Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg></i>')
            ->nickname('exercise')
            ->data('permission', 'exercise-list')
            ->link->attr(['class' => 'nav-link' ])
            ->href('#exercise');

            $menu->exercise->add('<span class="item-name">'.__('message.list_form_title',['form' => __('message.exercise')]).'</span>', ['route' => 'exercise.index'])
                ->data('permission', 'exercise-list')
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('exercise.index')) ? 'nav-link active' : 'nav-link']);

            $menu->exercise->add('<span class="item-name">'.__('message.add_form_title',['form' => __('message.exercise')]).'</span>', ['route' => 'exercise.create'])
                ->data('permission', [ 'exercise-add', 'exercise-edit'])
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('exercise.create')) || request()->is('exercise/*/edit') ? 'nav-link active' : 'nav-link']);

        $menu->add('<span class="item-name">'.__('message.workout').'</span>', ['class' => ''])
            ->prepend('<i class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="15" cy="4" r="2" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M11 16.0002V14.3667C11 13.8177 10.7561 13.297 10.3344 12.9455L9.33793 12.1152C8.61946 11.5164 8.57018 10.43 9.2315 9.76871L10.8855 8.11473C11.4193 7.5809 11.2452 6.67671 10.5513 6.37932C9.26627 5.82861 7.79304 5.94205 6.60752 6.68301L4.5 8.00021" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M7 14L6.67157 14.3284C6.09351 14.9065 5.80448 15.1955 5.43694 15.3478C5.0694 15.5 4.66065 15.5 3.84315 15.5H3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M12.5 10H15.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M19.4888 22.0001H3.08684C2.48659 22.0001 2 21.5135 2 20.9133C2 20.3853 2.37943 19.9337 2.89949 19.8427L19.0559 17.0153C20.5926 16.7464 22 17.9289 22 19.4889C22 20.8758 20.8757 22.0001 19.4888 22.0001Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                            <path d="M19.2916 8.88902L18.5499 8.77777L18.5499 8.77777L19.2916 8.88902ZM20.8773 7.22454L21.0244 7.95998L21.0244 7.95998L20.8773 7.22454ZM22.1471 7.73544C22.5533 7.6542 22.8167 7.25908 22.7354 6.85291C22.6542 6.44674 22.2591 6.18333 21.8529 6.26456L22.1471 7.73544ZM18.7417 17.6113L20.0333 9.00028L18.5499 8.77777L17.2583 17.3887L18.7417 17.6113ZM21.0244 7.95998L22.1471 7.73544L21.8529 6.26456L20.7302 6.48911L21.0244 7.95998ZM20.0333 9.00028C20.0862 8.64782 20.1178 8.44487 20.1568 8.2985C20.1744 8.23252 20.1885 8.19883 20.1965 8.18288C20.2002 8.17549 20.2024 8.17218 20.2029 8.17144C20.2034 8.17082 20.2034 8.17074 20.2037 8.17041L19.1177 7.13579C18.8906 7.37412 18.7782 7.64686 18.7076 7.91172C18.6418 8.15825 18.5978 8.45884 18.5499 8.77777L20.0333 9.00028ZM20.7302 6.48911C20.414 6.55235 20.1159 6.61086 19.8728 6.68852C19.6117 6.77197 19.3447 6.89746 19.1177 7.13579L20.2037 8.17041C20.2041 8.17009 20.2041 8.17 20.2047 8.16955C20.2054 8.169 20.2086 8.1666 20.2159 8.16256C20.2314 8.15385 20.2644 8.13813 20.3294 8.11734C20.4737 8.07123 20.6749 8.02987 21.0244 7.95998L20.7302 6.48911Z" fill="currentColor"/>
                    </svg></i>')
            ->nickname('workout')
            ->data('permission', 'workout-list')
            ->link->attr(['class' => 'nav-link' ])
            ->href('#workout');

            $menu->workout->add('<span class="item-name">'.__('message.list_form_title',['form' => __('message.workout')]).'</span>', ['route' => 'workout.index'])
                ->data('permission', 'workout-list')
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('workout.index')) ? 'nav-link active' : 'nav-link']);

            $menu->workout->add('<span class="item-name">'.__('message.add_form_title',['form' => __('message.workout')]).'</span>', ['route' => 'workout.create'])
                ->data('permission', [ 'workout-add', 'workout-edit'])
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('workout.create')) || request()->is('workout/*/edit') ? 'nav-link active' : 'nav-link']);

            $menu->workout->add('<span class="item-name">'.__('message.list_form_title',['form' => __('message.workouttype')]).'</span>', ['route' => 'workouttype.index'])
                ->data('permission', 'workouttype-list')
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('workouttype.index')) ? 'nav-link active' : 'nav-link']);

             $menu->add('<span class="item-name">'.__('message.schedule').'</span>', ['class' => ''])
            ->prepend('<i class="icon">
                    <img src="/images/svg/watch.svg" width="24"/></i>')
            ->nickname('schedule')
            ->data('permission', 'workout-list')
            ->link->attr(['class' => 'nav-link' ])
            ->href('#schedule');

            $menu->schedule->add('<span class="item-name">'.__('message.list_form_title',['form' => __('message.schedule')]).'</span>', ['route' => 'schedule.index'])
                ->data('permission', 'workout-list')
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('schedule.index')) ? 'nav-link active' : 'nav-link']);

            $menu->schedule->add('<span class="item-name">'.__('Calendar',['form' => __('message.schedule')]).'</span>', ['route' => 'schedule.calendar'])
                ->data('permission', 'workout-list')
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('schedule.calendar')) ? 'nav-link active' : 'nav-link']);

        $menu->add('<span class="item-name">'.__('goal').'</span>', ['class' => ''])
            ->prepend('<i class="icon"><img src="/images/svg/goal.svg" width="30"/>
                    </i>')
            ->nickname('goal')
            ->data('permission', 'goal-list')
            ->link->attr(['class' => 'nav-link' ])
            ->href('#goal');

            $menu->goal->add('<span class="item-name">'.__('message.list_form_title',['form' => __('goal')]).'</span>', ['route' => 'goal.index'])
                ->data('permission', 'goal-list')
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('goal.index')) ? 'nav-link active' : 'nav-link']);

            $menu->goal->add('<span class="item-name">'.__('message.add_form_title',['form' => __('goal')]).'</span>', ['route' => 'goal.create'])
                ->data('permission', [ 'goal-add', 'goal-edit'])
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('goal.create')) || request()->is('goal/*/edit') ? 'nav-link active' : 'nav-link']);


        $menu->add('<span class="item-name">'.__('message.level').'</span>', ['class' => ''])
            ->prepend('<i class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 22H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M3 11C3 10.0572 3 9.58579 3.29289 9.29289C3.58579 9 4.05719 9 5 9C5.94281 9 6.41421 9 6.70711 9.29289C7 9.58579 7 10.0572 7 11V17C7 17.9428 7 18.4142 6.70711 18.7071C6.41421 19 5.94281 19 5 19C4.05719 19 3.58579 19 3.29289 18.7071C3 18.4142 3 17.9428 3 17V11Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M10 7C10 6.05719 10 5.58579 10.2929 5.29289C10.5858 5 11.0572 5 12 5C12.9428 5 13.4142 5 13.7071 5.29289C14 5.58579 14 6.05719 14 7V17C14 17.9428 14 18.4142 13.7071 18.7071C13.4142 19 12.9428 19 12 19C11.0572 19 10.5858 19 10.2929 18.7071C10 18.4142 10 17.9428 10 17V7Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M17 4C17 3.05719 17 2.58579 17.2929 2.29289C17.5858 2 18.0572 2 19 2C19.9428 2 20.4142 2 20.7071 2.29289C21 2.58579 21 3.05719 21 4V17C21 17.9428 21 18.4142 20.7071 18.7071C20.4142 19 19.9428 19 19 19C18.0572 19 17.5858 19 17.2929 18.7071C17 18.4142 17 17.9428 17 17V4Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg></i>')
            ->nickname('level')
            ->data('permission', 'level-list')
            ->link->attr(['class' => 'nav-link' ])
            ->href('#level');

            $menu->level->add('<span class="item-name">'.__('message.list_form_title',['form' => __('message.level')]).'</span>', ['route' => 'level.index'])
                ->data('permission', 'level-list')
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('level.index')) ? 'nav-link active' : 'nav-link']);

            $menu->level->add('<span class="item-name">'.__('message.add_form_title',['form' => __('message.level')]).'</span>', ['route' => 'level.create'])
                ->data('permission', [ 'level-add', 'level-edit'])
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('level.create')) || request()->is('level/*/edit') ? 'nav-link active' : 'nav-link']);

        $menu->add('<span class="item-name">'.__('message.pushnotification').'</span>', ['class' => ''])
            ->prepend('<i class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.7491 9.70957V9.00497C18.7491 5.13623 15.7274 2 12 2C8.27256 2 5.25087 5.13623 5.25087 9.00497V9.70957C5.25087 10.5552 5.00972 11.3818 4.5578 12.0854L3.45036 13.8095C2.43882 15.3843 3.21105 17.5249 4.97036 18.0229C9.57274 19.3257 14.4273 19.3257 19.0296 18.0229C20.789 17.5249 21.5612 15.3843 20.5496 13.8095L19.4422 12.0854C18.9903 11.3818 18.7491 10.5552 18.7491 9.70957Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg></i>')
            ->nickname('pushnotification')
            ->data('permission', 'pushnotification-list')
            ->link->attr(['class' => 'nav-link' ])
            ->href('#pushnotification');

            $menu->pushnotification->add('<span class="item-name">'.__('message.list_form_title',['form' => __('message.pushnotification')]).'</span>', ['route' => 'pushnotification.index'])
                ->data('permission', 'pushnotification-list')
                ->prepend('<i class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="10" viewBox="0 0 24 24" fill="currentColor"><g><circle cx="12" cy="12" r="8" fill="currentColor"></circle></g></svg></i>')
                ->link->attr(['class' => activeRoute(route('pushnotification.index')) ? 'nav-link active' : 'nav-link']);


            $menu->add('<span class="item-name">'.__('message.achievements').'</span>', ['route' => 'achievements'])
            ->prepend('<i class="icon">
                    <img src="/images/svg/goal.svg" width="24"/></i>')
            ->link->attr([ 'class' => activeRoute(route('achievements')) ? 'nav-link active' : 'nav-link' ]);

                        $menu->add('<span class="item-name">'.__('message.rewards').'</span>', ['route' => 'rewards'])
            ->prepend('<i class="icon">
                    <img src="/images/badges/TROPHY.svg" width="24"/></i>')
            ->link->attr([ 'class' => activeRoute(route('rewards')) ? 'nav-link active' : 'nav-link' ]);

            $menu->add('<span class="item-name">'.__('message.notifications').$badge.'</span>', ['route' => 'notifications'])
            ->prepend('<i class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.7491 9.70957V9.00497C18.7491 5.13623 15.7274 2 12 2C8.27256 2 5.25087 5.13623 5.25087 9.00497V9.70957C5.25087 10.5552 5.00972 11.3818 4.5578 12.0854L3.45036 13.8095C2.43882 15.3843 3.21105 17.5249 4.97036 18.0229C9.57274 19.3257 14.4273 19.3257 19.0296 18.0229C20.789 17.5249 21.5612 15.3843 20.5496 13.8095L19.4422 12.0854C18.9903 11.3818 18.7491 10.5552 18.7491 9.70957Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M7.5 19C8.15503 20.7478 9.92246 22 12 22C14.0775 22 15.845 20.7478 16.5 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg></i>')
            ->link->attr([ 'class' => activeRoute(route('notifications')) ? 'nav-link active' : 'nav-link' ]);

        $menu->add('<span class="item-name">'.__('message.setting').'</span>', ['route' => 'setting.index'])
            ->prepend('<i class="icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M13.7639 2.15224C13.3963 2 12.9304 2 11.9985 2C11.0666 2 10.6007 2 10.2332 2.15224C9.7431 2.35523 9.35375 2.74458 9.15076 3.23463C9.0581 3.45834 9.02184 3.7185 9.00765 4.09799C8.98679 4.65568 8.70079 5.17189 8.21748 5.45093C7.73417 5.72996 7.14412 5.71954 6.65073 5.45876C6.31498 5.2813 6.07154 5.18262 5.83147 5.15102C5.30558 5.08178 4.77372 5.22429 4.3529 5.5472C4.03728 5.78938 3.80431 6.1929 3.33837 6.99993C2.87243 7.80697 2.63946 8.21048 2.58753 8.60491C2.51829 9.1308 2.6608 9.66266 2.98371 10.0835C3.1311 10.2756 3.33824 10.437 3.65972 10.639C4.13233 10.936 4.43643 11.4419 4.43639 12C4.43636 12.5581 4.13228 13.0639 3.65972 13.3608C3.33818 13.5629 3.13101 13.7244 2.98361 13.9165C2.66071 14.3373 2.5182 14.8691 2.58743 15.395C2.63936 15.7894 2.87233 16.193 3.33827 17C3.80421 17.807 4.03718 18.2106 4.3528 18.4527C4.77362 18.7756 5.30548 18.9181 5.83137 18.8489C6.07143 18.8173 6.31486 18.7186 6.65057 18.5412C7.14401 18.2804 7.73409 18.27 8.21743 18.549C8.70077 18.8281 8.98679 19.3443 9.00765 19.9021C9.02184 20.2815 9.05811 20.5417 9.15076 20.7654C9.35375 21.2554 9.7431 21.6448 10.2332 21.8478C10.6007 22 11.0666 22 11.9985 22C12.9304 22 13.3963 22 13.7639 21.8478C14.2539 21.6448 14.6433 21.2554 14.8463 20.7654C14.9389 20.5417 14.9752 20.2815 14.9894 19.902C15.0103 19.3443 15.2962 18.8281 15.7795 18.549C16.2628 18.2699 16.853 18.2804 17.3464 18.5412C17.6821 18.7186 17.9255 18.8172 18.1656 18.8488C18.6915 18.9181 19.2233 18.7756 19.6442 18.4527C19.9598 18.2105 20.1927 17.807 20.6587 16.9999C21.1246 16.1929 21.3576 15.7894 21.4095 15.395C21.4788 14.8691 21.3362 14.3372 21.0133 13.9164C20.8659 13.7243 20.6588 13.5628 20.3373 13.3608C19.8647 13.0639 19.5606 12.558 19.5607 11.9999C19.5607 11.4418 19.8647 10.9361 20.3373 10.6392C20.6588 10.4371 20.866 10.2757 21.0134 10.0835C21.3363 9.66273 21.4789 9.13087 21.4096 8.60497C21.3577 8.21055 21.1247 7.80703 20.6588 7C20.1928 6.19297 19.9599 5.78945 19.6442 5.54727C19.2234 5.22436 18.6916 5.08185 18.1657 5.15109C17.9256 5.18269 17.6822 5.28136 17.3465 5.4588C16.853 5.71959 16.263 5.73002 15.7796 5.45096C15.2963 5.17191 15.0103 4.65566 14.9894 4.09794C14.9752 3.71848 14.9389 3.45833 14.8463 3.23463C14.6433 2.74458 14.2539 2.35523 13.7639 2.15224Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg></i>')
            ->link->attr([ 'class' => activeRoute(route('setting.index')) ? 'nav-link active' : 'nav-link' ]);
    })->filter(function ($item) {
        return checkMenuRoleAndPermission($item);
    });
@endphp
<ul class="navbar-nav iq-main-menu"  id="sidebar">

    <li><hr class="hr-horizontal"></li>

    @include(config('laravel-menu.views.bootstrap-items'), ['items' => $MyNavBar->roots()])
</ul>
