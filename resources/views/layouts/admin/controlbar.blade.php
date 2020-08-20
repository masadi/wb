<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
        <h5>{{ Auth::user()->name }}</h5>
        <a class="d-block" href="javascript:{}" onclick="document.getElementById('logout-form-controlbar').submit();">Logout</a>
        <form id="logout-form-controlbar" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>
