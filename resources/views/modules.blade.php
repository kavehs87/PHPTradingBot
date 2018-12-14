@extends('layout')

@section('body')

    <h1>
        modules
    </h1>

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
                    @if(\App\Modules::factory($module) != null)
                        {{\App\Modules::factory($module)->isActive() ? "Active" : "Inactive"}}
                    @endif
                </td>
                <td>
                    @if(\App\Modules::factory($module) == null)
                        <a href="{{route('installModule',$module)}}" class="btn btn-success">Install</a>
                    @else
                        @if(\App\Modules::factory($module)->isActive())
                            <a href="{{route('disableModule',\App\Modules::factory($module)->id)}}"
                               class="btn btn-warning">Disable</a>
                        @else
                            <a href="{{route('enableModule',\App\Modules::factory($module)->id)}}"
                               class="btn btn-primary">Enable</a>
                            <a href="{{route('uninstallModule',\App\Modules::factory($module)->id)}}"
                               class="btn btn-danger">Uninstall</a>
                        @endif
                    @endif
                </td>
            </tr>

        @empty

        @endforelse
        </tbody>
    </table>

@endsection