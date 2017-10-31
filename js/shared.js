function showMessage(msg, transient, length)
  {
      if (typeof(transient)==='undefined') transient = true;
      $("#fixedmsg").html(msg);
      $("#fixedmsg").animate({marginBottom: "0px"},200);
      if (transient)
      {
          if (typeof(length)==='undefined')
      setTimeout(hideMessage,2000);
      else
      setTimeout(hideMessage,length);
      }
  }
  function hideMessage()
  {
      $("#fixedmsg").animate({marginBottom: "-40px"},200);
  }
  function init()
  {
  }