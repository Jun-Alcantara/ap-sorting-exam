@extends('layout')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-6 offset-3">
                <h3 class="h3">{{ $group->name ?? 'No Group Name' }}</h3>
                <a href="{{ route('groups.index') }}">
                    <i class="fa fa-arrow-left"></i>
                    Back to selection
                </a>
                <hr>
                <table id="participants-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Participant Name</th>
                            <th class="text-center" colspan="4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group->Participants as $participant)
                            <tr class="participant-row" data-id="{{ $participant->id }}" data-rank={{ $participant->rank }}>
                                <td class="rank">{{ $participant->rank }}</td>
                                <td>{{ $participant->name }}</td>
                                <td>
                                    <a href="#" class="top">
                                        <i class="fa fa-arrow-up"></i> Top
                                    </a>
                                </td>
                                <td>
                                    <a href="#" class="move-up">
                                        <i class="fa fa-caret-up"></i> Up
                                    </a>
                                </td>
                                <td>
                                    <a href="#" class="move-down">
                                        <i class="fa fa-caret-down"></i> Down
                                    </a>
                                </td>
                                <td>
                                    <a href="#" class="bottom">
                                        <i class="fa fa-arrow-down"></i> Bottom
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="status-alert" class="alert alert-primary" style="display: none;" role="alert">
                  Saving ..
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const tableBody = $('table#participants-table > tbody')
        const statusAlert = $('div#status-alert')
        let updateRankRequest = null

        function move (position, element) {
            let parentRow = element.closest('tr')
            let currentParticipantRank = tableBody.children().index( parentRow )

            if ( position === 'to-top' ) {
                if ( currentParticipantRank === 0 ) return
                tableBody.prepend(parentRow)
            }

            if ( position === 'up' ) {
                if ( currentParticipantRank === 0 ) return

                let upperRow = parentRow.prev()
                parentRow.insertBefore( upperRow )
            }

            if ( position === 'down' ) {
                if ( currentParticipantRank === tableBody.children().length - 1 ) return

                let lowerRow = parentRow.next()
                parentRow.insertAfter( lowerRow )
            }

            if ( position === 'to-bottom' ) {
                if ( currentParticipantRank === tableBody.children().length - 1 ) return
                tableBody.append(parentRow)
            }

            updateRanking()
        }

        $('a.top').click(function (e) {
            e.preventDefault()
            move('to-top', $(this))
        })

        $('a.bottom').click(function (e) {
            e.preventDefault()
            move('to-bottom', $(this))
        })

        $('a.move-up').click(function (e) {
            e.preventDefault()
            move('up', $(this))
        })

        $('a.move-down').click(function (e) {
            e.preventDefault()
            move('down', $(this))
        })

        function updateRanking () {
            let rows = $('tr.participant-row')
            let updatedRanking = []

            rows.each((updatedRank, el) => {
                updatedRank++
                let element = $(el)
                let participantId = element.data('id')

                firstChild = element.find('td.rank').html(updatedRank)
                updatedRanking.push({participantId, updatedRank})
            })

            updateRankRequest = $.ajax({
                url: `{{ route('participants.update.ranking', $group) }}`,
                method: 'POST',
                data: {
                    _token: `{{ csrf_token() }}`,
                    ranking: updatedRanking
                },
                beforeSend () {
                    if ( updateRankRequest != null ) {
                        updateRankRequest.abort()
                    }

                    statusAlert.html(`<i class="fa fa-spinner fa-spin"></i> Saving ranks...`)
                    statusAlert.show()
                },
                success (response) {
                    statusAlert.html(`<i class="fa fa-check"></i> ${response.message}`)

                    setTimeout(function(){
                        statusAlert.fadeOut()
                    }, 1000)
                },
                error (e) {
                    console.log(e)
                }
            })
        }
    </script>
@endpush
