<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('All Agents') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
          <div class="breadcrumb-item"><a href="{{ route('agent.list') }}">{{__("Agent")}}</a></div>
          <div class="breadcrumb-item"><a href="{{ route('agent.list') }}">{{__("All Agents")}}</a></div>
        </div>
    </x-slot>

    <div>
        <div class="bg-gray-100 text-gray-900 tracking-wider leading-normal">
          <div class="p-8 pt-4 mt-2 bg-white">
              <div class="flex pb-4 -ml-3">

              </div>

              <div class="row">
                  <div class="table-responsive">
                      <table class="table table-bordered table-striped text-sm text-gray-600">
                          <thead>
                              <tr>
                                <th>{{__("#")}}  </th>
                                <th>{{__("ID")}}  </th>
                                <th>{{__("First Name")}}</th>
                                <th>{{__("Last Name")}}</th>
                                <th>{{__("Email")}}</th>
                                <th>{{__("Action")}}</th>
                              </tr>
                          </thead>
                          <tbody>

                              @forelse($owners as $key=>$owner)
                              <tr>
                                  <td>{{ $key + 1 }}</td>
                                  <td>{{$owner['id']}}</td>
                                  <td>{{$owner['first_name']}}</td>
                                  <td>{{$owner['last_name']}}</td>
                                  <td>{{$owner['email']}}</td>
                                  <td class="whitespace-no-wrap row-action--icon">
                                      @if(!$owner['generated'])
                                      <a role="button" href="{{ route('agent.generate', ['hubspotId' => base64_encode($owner['id'])])}}" class="mr-3 btn btn-primary"><i class="fas fa-plus-square"></i> {{__("Generate Profile")}}</a>
                                      @endif
                                  </td>
                              </tr>
                              @empty
                              @endforelse
                          </tbody>
                      </table>
                  </div>
              </div>


          </div>
        </div>
    </div>
</x-app-layout>
