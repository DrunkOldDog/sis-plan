<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">Hotel Empresarial</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
          <ul class="navbar-nav mr-auto">

              @if(auth()->user()->isAdmin != 0)
              <li class="nav-item">
                  <a class="nav-link" href="{{ url('reservas') }}">Reservas</a>
                </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ url('eventos') }}">Paquetes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ url('promociones') }}">Promociones</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ url('ambientes') }}">Ambientes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Precios</a>
              </li>
              @endif
              @if(auth()->user()->isAdmin == 0)
              <li class="nav-item">
                  <a class="nav-link" href="/">Home</a>
                </li>
               <li class="nav-item">
                  <a class="nav-link" href="{{ route('eve.per', 'crear')}}">Reserva Personalizada</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('eventos') }}">Mis Paquetes</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="{{ url('reservas') }}">Mis Reservas</a>
                    </li>
              @endif
              @if(auth()->user()->isAdmin == 1)
              <li class="nav-item">
                  <a class="nav-link" href="#">Dashboard</a>
                </li>
              @endif
          </ul>
          <ul class="navbar-nav mr-sm-2">
              <li class="nav-item dropdown">
                  <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                      {{ Auth::user()->username }} <span class="caret"></span>
                  </a>

                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                      @if(auth()->user()->isAdmin == 1)
                      <a class="dropdown-item" href="{{ url('roles') }}">
                        Agregar Roles
                      </a>
                      @endif
                      <a class="dropdown-item" href="{{ route('logout') }}"
                         onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                          {{ __('Logout') }}
                      </a>

                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                          @csrf
                      </form>
                  </div>
              </li>
          </ul>
        </div>
      </nav>
    
  