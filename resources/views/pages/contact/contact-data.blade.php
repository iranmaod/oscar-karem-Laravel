<x-app-layout>
    <x-slot name="header_content">
        <h1>{{ __('All Contacts') }}</h1>

        <div class="hs-ok-breadcrumb section-header-breadcrumb">
          <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">{{__("Dashboard")}}</a></div>
          <div class="breadcrumb-item"><a href="{{ route('contact') }}">{{__("Contacts")}}</a></div>
          <div class="breadcrumb-item"><a href="{{ route('contact') }}">{{__("All Contacts")}}</a></div>
        </div>
    </x-slot>

    <div>
        <div class="bg-gray-100 text-gray-900 tracking-wider leading-normal">
          <div class="p-8 pt-4 mt-2 bg-white">
              <div class="flex pb-4 -ml-3">
                <form class="form-row hs-ok-contact-search-wrap" action="{{ route('contact')}}" method="get">
                  <div class="form-group col-sm-12 col-md-12 hs-ok-search-input">
                    <label class="control-label">{{__("Search")}}:</label>
                    <input type="text" class="form-control" value="{{request('query')}}" name="query" placeholder="{{__('Search Firstname, Lastname, Email Address or Phone Number')}}" />
                  </div>


                  <div class="form-group col-sm-6 hs-ok-search-submit">
                    <label class="control-label"> </label>
                    <button type="submit" class="btn btn-primary form-control">{{__("Search")}}</button>
                  </div>
                  @if(request('query'))
                  <div class="form-group col-sm-6 hs-ok-search-reset">
                    <label class="control-label"> </label>
                    <a href="{{route('contact')}}" class="btn btn-primary form-control">{{__("Reset")}}</a>
                  </div>
                  @endif
                </form>
              </div>

              <div class="row">
                  <div class="table-responsive">
                      <table class="table table-bordered table-striped text-sm text-gray-600">
                          <thead>
                              <tr>
                                <th>{{__("ID")}}  </th>
                                <th>{{__("First Name")}}</th>
                                <th>{{__("Last Name")}}</th>
                                <th>{{__("Email")}}</th>
                                <th>{{__("Phone")}}</th>
                                <th>{{__("Action")}}</th>
                              </tr>
                          </thead>
                          <tbody>

                              @forelse($contacts as $contact)
                              <tr>
                                  <td>{{$contact['vid']}}</td>
                                  <td>{{$contact['firstname']}}</td>
                                  <td>{{$contact['lastname']}}</td>
                                  <td>{{$contact['email']}}</td>
                                  <td>{{$contact['phone']}}</td>
                                  <td class="whitespace-no-wrap row-action--icon">
                                      <a role="button" href="{{ route('contact.order', ['contactId' => base64_encode($contact['vid'])])}}" class="mr-3 btn btn-primary"><i class="fas fa-plus-square"></i> {{__("Generate Order")}}</a>
                                  </td>
                              </tr>
                              @empty
                              @endforelse
                          </tbody>
                      </table>
                  </div>
              </div>
              <div class="row">
                <ul class="pager">
                  @if($nextPage)
                  <li><a href="{{route('contact', array('after' => $nextPage))}}" class="btn btn-primary">{{__("Next")}}</a></li>
                  @endif
                </ul>
              </div>


          </div>
        </div>
    </div>
</x-app-layout>
