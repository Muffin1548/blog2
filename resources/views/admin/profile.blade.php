@extends('layouts.app')
@section('content')
    <div>
        <ul class="list-group">

            <li class="list-group-item panel-body">
                <table class="table-padding">
                    <style>
                        .table-padding td {
                            padding: 3px 8px;
                        }
                    </style>
                    <tr>
                        <td>Total Posts</td>
                        <td> {{$user['posts_count']}}</td>
                        @if($user['posts_count'])
                            <td><a href="{{ url('user/'.$user['id'].'/posts') }}">Show All</a></td>
                        @endif
                    </tr>
                </table>
            </li>
            <li class="list-group-item">
                Total Comments {{$user['comments_count']}}
            </li>
        </ul>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Latest Posts</h3>
        </div>
        <div class="panel-body">
            @if(!empty($user['latest_posts']))
                @foreach($user['latest_posts'] as $latest_post)
                    <p>
                        <strong><a href="{{ url(''.$latest_post->slug) }}">{{ $latest_post->title }}</a></strong>
                        <span class="well-sm">On {{ $latest_post->created_at->format('M d,Y \a\t h:i a') }}</span>
                    </p>
                @endforeach
            @else
                <p>You have not written any post till now.</p>
            @endif
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Latest Comments</h3>
        </div>
        <div class="list-group">
            @if(!empty($user['latest_comments']))
                @foreach($user['latest_comments'] as $latest_comment)
                    <div class="list-group-item">
                        <p>{{ $latest_comment->body }}</p>
                        <p>On {{ $latest_comment->created_at->format('M d,Y \a\t h:i a') }}</p>

                    </div>
                @endforeach
            @else
                <div class="list-group-item">
                    <p>You have not commented till now. Your latest 5 comments will be displayed here</p>
                </div>
            @endif
        </div>
    </div>
@endsection
