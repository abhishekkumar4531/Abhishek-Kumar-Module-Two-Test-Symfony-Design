/**
 * When page load then this function will be execute.
 */
$( document ).ready(function() {
  /**
   * When user click on add button.
   * Add button will be add the item in umnarked item list.
   */
  $("#addBtn").on('click', function(e) {
    e.preventDefault;
    var fetchNewItem = $("#newItem").val();

    //If user click on add button with some value then if block will be execute.
    //It will communicate with database with the help of php and update the list.
    //If user try to add and empty list then alert with error message will be generate.
    if(fetchNewItem  != "") {
      $.ajax({
        type: "POST",
        url: "/home/add",
        data: {newItem : fetchNewItem },
        dataType: "json",
        success: function(data) {
          if(data.status) {
            displayListData();
          }
          else {
            alert("Check error during insertion!!!");
          }
        }
      });
    }
    else {
      alert("Please fill the list feild!!!");
    }
  });

  /**
   * When user want to update their specific item.
   * When user click on edit option then a prompt will be open where user can
   * change the itemValue.
   * After that collect the data from promt if changed and then update the itemValue
   * and also display the updated itemValue.
   */
  $(document).on('click','.editBtn',function(e){
    var itemId = $(this).data("id");
    var itemValue = $(this).data("value");
    var editValue = prompt("Edit your value :", itemValue);


    if(editValue === '') {
      editValue = itemValue;
    }

    $.ajax({
      type: "POST",
      url: "/home/edit",
      data: {editItemId : itemId, editItemValue : editValue},
      dataType: 'json',
      success: function(data) {
        if(data.status) {
          displayListData();
        }
        else {
          alert("Check error while editing");
        }
      }
    });
  });

  /**
   * It will delete item from unmarked item list.
   * It will fetch the which itemId of a item, which item user want to delete.
   * And then call the deleteData with parameter of itemId.
   */
  $(document).on('click','.deleteBtn',function(e){
    var itemId = $(this).data("id");
    deleteData(itemId);
  });

  /**
   * It will delete item from marked item list.
   * It will fetch the which itemId of a item, which item user want to delete.
   * And then call the deleteMarkedData with parameter of itemId.
   */
  $(document).on('click','.deleteMarkedBtn',function(e){
    var itemId = $(this).data("id");
    deleteMarkedData(itemId);
  });

  /**
   * For marked the an item of unmarked item list.
   * When user clicked on marked button then two things will be happen,
   * first data will removed from current unmarked list and then display the
   * data in marked item list.
   */
  $(document).on('click','.markedBtn',function(e){
    var itemId = $(this).data("id");
    var itemValue = $(this).data("value");
    $.ajax({
      type: "POST",
      url: "/home/mark",
      data: {markItemValue : itemValue},

      success: function(data) {
        if(data.status) {
          deleteData(itemId);
          displayMarkedData();
        }
        else {
          alert("Check error after deletion!!!");
        }
      }
    });
  });

  /**
   * This will recive the itemId of an unmarked item as a parameter.
   * It will remove the item from unmarked item list whose item id will be given,
   * through parameter
   * @param {int} itemId
   */
  function deleteData(itemId) {
    $.ajax({
      type: "POST",
      url: "/home/delete",
      data: {delItemId : itemId},

      success: function(data) {
        if(data.status) {
          displayListData();
        }
        else {
          alert("Check error after deletion!!!");
        }
      }
    });
  }

  /**
   * This will recive the itemId of an unmarked item as a parameter.
   * It will remove the item from marked item list whose item id will be given,
   * through parameter
   * @param {int} itemId
   */
  function deleteMarkedData(itemId) {
    $.ajax({
      type: "POST",
      url: "/home/deletemarked",
      data: {delItemId : itemId},

      success: function(data) {
        if(data.status) {
          displayMarkedData();
        }
        else {
          alert("Check error after deletion!!!");
        }
      }
    });
  }

  /**
   * It will respponsible for display the ummarked item list.
   */
  function displayListData() {
    $.ajax({
      type: "POST",
      url: "/home/display",

      success: function(data) {
        $("#addedItem").html(data);
      }
    });
  }

  /**
   * It will respponsible for display the marked item list.
   */
  function displayMarkedData() {
    $.ajax({
      type: "POST",
      url: "/home/displaymarked",

      success: function(data) {
        $("#markedItem").html(data);
      }
    });
  }

  //On page load display the unmarked item list.
  displayListData();

  //On page load display the marked item list.
  displayMarkedData();
});
