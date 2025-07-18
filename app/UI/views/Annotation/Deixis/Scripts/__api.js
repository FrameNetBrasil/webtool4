const __api = {
    getObject: async (idDynamicObject) => {
        return await ky.get("/annotation/deixis/object/"+ idDynamicObject).json();
        //
        //
        //
        // let result = null;
        // await $.ajax({
        //     url: "/annotation/deixis/object/" + idDynamicObject,
        //     method: "GET",
        //     dataType: "json",
        //     // data: {
        //     //     _token: annotation._token
        //     // },
        //     success: (response) => {
        //         console.log(response);
        //         result = response;
        //     }
        // });
        // return result;
    }
};
