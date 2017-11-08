import {Component, Input, OnInit} from '@angular/core';
import {PostService} from '../../services/post.service';
import {NotificationsService} from 'angular2-notifications';
import {EmitterService} from '../../services/emitter.service';

@Component({
  selector: 'app-add-comment',
  templateUrl: './add-comment.component.html'
})
export class AddCommentComponent implements OnInit {

  @Input() post: any;

  userName: string;
  userEmail: string;
  userComment: string;

  constructor(private _postService: PostService,
              private notificationsService: NotificationsService) {
  }

  ngOnInit() {
  }

  public submitComment() {

    this._postService.addComment(this.post.slug, this.userName, this.userComment, this.userEmail).subscribe(
      response => {
        if (response.success) {
          EmitterService.get('ADD_COMMENT').emit('success');
          this.userName = null;
          this.userComment = null;
          this.userEmail = null;
          this.notificationsService.success('Success', response.message);
        } else {
          this.notificationsService.error('Error', response.message);
        }
      },
      errorResponse => {
        if (errorResponse.status !== 422) {
          this.notificationsService.warn('Warning', 'Something went wrong.');
        } else {
          const errors = errorResponse.error.errors;
          this.notificationsService.warn('Warning', errors[Object.keys(errors)[0]]);
        }
      }
    );
  }
}
