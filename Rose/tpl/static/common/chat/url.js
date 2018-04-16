/**
 *  * URL
 *   * @author damon
 *    */
define(function(require, exports, module){

    var base = 'http://api.m.34team.com/';
    var _statusDomain = 'http://static.34team.com/';
    var _domain = 'http://www.34team.com/';
    exports.statusDomain = _statusDomain;
    exports.domain = _domain;
    var URL = {
        'jssdk'       : base + 'jssdk/jssdk/get',
        'jweixin'     : 'http://res.wx.qq.com/open/js/jweixin-1.0.0.js',
        //work
        'workPunch'   : base + 'work/app/punch_in_clock',
        'getUserInfo' : base + 'work/app/get_user_info',
        'getRecord'   : base + 'work/app/get_record_info',
        'todayPunch'  : base + 'work/app/get_user_today_punch_info',
        'getUserWorkRecord': base + 'work/app/get_user_record',
        'workSetting': base + 'work/app/setting',
        'getUserSetting': base + 'work/app/get_user_setting',
        //approve
        'approveApply' : base + 'approve/app/apply',
        'getApproveList' : base + 'approve/app/get_approve_record_list',
        'getApproveDetail' : base + 'approve/app/get_approve_detail',
        'myApprove'  : base + 'approve/app/my_approve',
        'approveOpt' : base + 'approve/app/approve_opt',
        'noticeToApprove' : base + 'approve/app/notice_to_approve',
        //member
        'getImtoken':base+'rongyun/api/gettoken',
        'getPhoneBookList':base+'member/app/get_phone_book_list',
        'getmember':base+'member/app/getmember',
        'messageseverlogin':base+'member/app/message_sever_login',
        'getorganizational':base+'member/app/get_organizational',
        'getcompany':base+'member/app/getcompany',
        'getDepartmentMember':base+'member/app/get_department_member',
        'getMyCompanyCard':base+'member/app/get_my_company_card',
        //chat
        'chatadd':base+'chat/api/chatadd',
        'sendwxmessage':base+'chat/api/sendwxmessage',
        'getchatlist':base+'chat/api/getchatlist',
        'getchatuserlist':base+'chat/api/getchatuserlist',
        'getchatnoread':base+'chat/api/getchatnoread',
        //outwork
        'setWork' : base + 'outwork/app/set_work',
        'getWork' : base + 'outwork/app/get_work',
        'setPraise' : base + 'outwork/app/set_praise',
        'myInPraise':base+'outwork/app/my_in_praise',
        'myPutPraise':base+'outwork/app/my_put_praise',
    	//exam
    	'getclassify':base+'train/app/get',
    	'getarticle':base+'train/app/getarticle',
        'getArticleSrc':base+'train/app/get_article_src',
        'setArticleLike':base+'train/app/set_article_like',
        'checkArticleLike':base+'train/app/check_article_like',
        'getArticleQuestionList':base+'train/app/get_article_question_list',
        'getExamNotice':base+'train/app/get_notice',
        'getExam':base+'train/app/get_exam',
        'setExamSign':base+'train/app/set_exam_sign',
	    'setExamSignAbsent':base+'train/app/set_exam_sign_absent',
        'checkExamStart':base+'train/app/check_exam_start',
        'getQuestion':base+'train/app/get_question',
        'addExamForWx':base+'train/app/add_exam_for_wx',
        'getUserExamById':base+'train/app/get_user_exam_by_id',
        'setArticleQuestionList':base+'train/app/set_article_question_list',
        //media
        'getMedia':base+'file/app/load_file_from_weixin',
    }


    exports.get = function(key){
        var sUrl = typeof(URL[key]) === 'undefined' ? null : URL[key];
        //return sUrl;
        var iPos = false;
        var cUrl = location.href;
        var iCPos= cUrl.indexOf('?');
        var sParam = '';
        if(iCPos !== -1){
            sParam = cUrl.substr(iCPos+1);

        }else{
            iCPos = cUrl.length;
        }
        sParam += '&__url__=' + cUrl.substr(0, iCPos);
        if (-1 !== (iPos=sUrl.indexOf('?'))) {
            return sUrl + '&' + sParam;
        }
        return sUrl + '?' + sParam;
    }
});
