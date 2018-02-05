/*  
 * @������ʾ�� ( ���ض���(load), ��ʾ����(tip), �ɹ�(success), ����(error), )  
 * @method  tipBox  
 * @description Ĭ�����ò���   
 * @time    2016-07-09
 * @param {Number} width -���  
 * @param {Number} height -�߶�         
 * @param {String} str -Ĭ������  
 * @param {Object} windowDom -���봰�� Ĭ�ϵ�ǰ����  
 * @param {Number} setTime -��ʱ��ʧ(����) Ĭ��Ϊ0 ����ʧ  
 * @param {Boolean} hasMask -�Ƿ���ʾ����  
 * @param {Boolean} hasMaskWhite -��ʾ��ɫ����   
 * @param {Boolean} clickDomCancel -����հ�ȡ��  
 * @param {Function} callBack -�ص����� (ֻ�ڿ�����ʱ��ʧʱ����Ч)  
 * @param {String} type -�������� (����,�ɹ�,ʧ��,��ʾ)  
 * @example   
 * new TipBox();   
 * new TipBox({type:'load',setTime:1000,callBack:function(){ alert(..) }});   
*/  
function TipBox(cfg){  
    this.config = {   
        width          : 180,      
        height         : 150,                 
        str            : '���ڴ���',       
        windowDom      : window,   
        setTime        : 0,     
        hasMask        : true,    
        hasMaskWhite   : false,   
        clickDomCancel : false,    
        callBack       : null,  
        type           : 'success'  
    }  
    $.extend(this.config,cfg);    
      
    //���ھ�retrun  
    if(TipBox.prototype.boundingBox) return;  
      
    //��ʼ��  
    this.render(this.config.type);    
    return this;   
};  
  
//���box  
TipBox.prototype.boundingBox = null;  
  
//��Ⱦ  
TipBox.prototype.render = function(tipType,container){    
    this.renderUI(tipType);   
      
    //���¼�  
    this.bindUI();   
      
    //��ʼ��UI  
    this.syncUI();   
    $(container || this.config.windowDom.document.body).append(TipBox.prototype.boundingBox);     
};  
  
//��ȾUI  
TipBox.prototype.renderUI = function(tipType){   
    TipBox.prototype.boundingBox = $("<div id='animationTipBox'></div>");         
    tipType == 'load'    && this.loadRenderUI();  
    tipType == 'success' && this.successRenderUI();   
    tipType == 'error'   && this.errorRenderUI();  
    tipType == 'tip'     && this.tipRenderUI();  
    TipBox.prototype.boundingBox.appendTo(this.config.windowDom.document.body);  
                  
    //�Ƿ���ʾ����  
    if(this.config.hasMask){  
        this.config.hasMaskWhite ? this._mask = $("<div class='mask_white'></div>") : this._mask = $("<div class='mask'></div>");  
        this._mask.appendTo(this.config.windowDom.document.body);  
    }     
      
    //��ʱ��ʧ  
    _this = this;  
    !this.config.setTime && typeof this.config.callBack === "function" && (this.config.setTime = 1);      
    this.config.setTime && setTimeout( function(){ _this.close(); }, _this.config.setTime );  
};  
  
TipBox.prototype.bindUI = function(){  
    _this = this;             
      
    //����հ�����ȡ��  
    this.config.clickDomCancel && this._mask && this._mask.click(function(){_this.close();});                         
};  
TipBox.prototype.syncUI = function(){             
    TipBox.prototype.boundingBox.css({  
        width       : this.config.width+'px',  
        height      : this.config.height+'px',  
        marginLeft  : "-"+(this.config.width/2)+'px',  
        marginTop   : "-"+(this.config.height/2)+'px'  
    });   
};  
  
//��ʾЧ��UI  
TipBox.prototype.tipRenderUI = function(){  
    var tip = "<div class='tip'>";  
        tip +="     <div class='icon'>i</div>";  
        tip +="     <div class='dec_txt'>"+this.config.str+"</div>";  
        tip += "</div>";  
    TipBox.prototype.boundingBox.append(tip);  
};  
  
//�ɹ�Ч��UI  
TipBox.prototype.successRenderUI = function(){  
    var suc = "<div class='success'>";  
        suc +=" <div class='icon'>";  
        suc +=      "<div class='line_short'></div>";  
        suc +=      "<div class='line_long'></div>  ";        
        suc +=  "</div>";  
        suc +=" <div class='dec_txt'>"+this.config.str+"</div>";  
        suc += "</div>";  
    TipBox.prototype.boundingBox.append(suc);  
};  
  
//����Ч��UI  
TipBox.prototype.errorRenderUI = function(){  
    var err  = "<div class='lose'>";  
        err +=  "   <div class='icon'>";  
        err +=  "       <div class='icon_box'>";  
        err +=  "           <div class='line_left'></div>";  
        err +=  "           <div class='line_right'></div>";  
        err +=  "       </div>";  
        err +=  "   </div>";  
        err +=  "<div class='dec_txt'>"+this.config.str+"</div>";  
        err +=  "</div>";  
    TipBox.prototype.boundingBox.append(err);  
};  
  
//���ض���load UI  
TipBox.prototype.loadRenderUI = function(){  
    var load = "<div class='load'>";  
        load += "<div class='icon_box'>";  
    for(var i = 1; i < 4; i++ ){  
        load += "<div class='cirBox"+i+"'>";  
        load +=     "<div class='cir1'></div>";  
        load +=     "<div class='cir2'></div>";  
        load +=     "<div class='cir3'></div>";  
        load +=     "<div class='cir4'></div>";  
        load += "</div>";  
    }  
    load += "</div>";  
    load += "</div>";  
    load += "<div class='dec_txt'>"+this.config.str+"</div>";  
    TipBox.prototype.boundingBox.append(load);  
};  
  
//�ر�  
TipBox.prototype.close = function(){      
    TipBox.prototype.destroy();
    this.destroy();  
    this.config.setTime && typeof this.config.callBack === "function" && this.config.callBack();                  
};  
  
//����  
TipBox.prototype.destroy = function(){  
    this._mask && this._mask.remove();  
    TipBox.prototype.boundingBox && TipBox.prototype.boundingBox.remove();   
    TipBox.prototype.boundingBox = null;  
};  