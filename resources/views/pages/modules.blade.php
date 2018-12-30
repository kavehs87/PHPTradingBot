@extends('layouts.app')

@section('content')


    <div class="card">
        <div class="card-header">
            modules
        </div>
        <div class="card-body">
            <table class="table table-hover table-responsive">
                <thead>
                <tr>
                    <th>
                        Module Name
                    </th>
                    <th>
                        Description
                    </th>
                    <th>
                        Status
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse(\App\Modules::getModules() as $module)

                    <tr>
                        <td>
                            {{$module}}
                        </td>
                        <td>
                            {{\App\Modules::getModuleWithNameSpace($module)::$description}}
                        </td>
                        <td>
                            @if(\App\Modules::init($module) != null)
                                {{\App\Modules::init($module)->isActive() ? "Active" : "Inactive"}}
                            @endif
                        </td>
                        <td>
                            @if(\App\Modules::init($module) == null)
                                <a href="{{route('installModule',$module)}}" class="btn btn-success">Install</a>
                            @else
                                @if(\App\Modules::init($module)->isActive())
                                    <a href="{{route('disableModule',\App\Modules::init($module)->id)}}"
                                       class="btn btn-warning">Disable</a>
                                @else
                                    <a href="{{route('enableModule',\App\Modules::init($module)->id)}}"
                                       class="btn btn-primary">Enable</a>
                                    <a href="{{route('uninstallModule',\App\Modules::init($module)->id)}}"
                                       class="btn btn-danger">Uninstall</a>
                                @endif
                            @endif
                        </td>
                    </tr>

                @empty

                @endforelse
                </tbody>
            </table>
        </div>
    </div>


@endsection