defmodule CalcWeb.PageController do
  use CalcWeb, :controller

  def home(conn, _params) do
    # The home page is often custom made,
    # so skip the default app layout.
    render(conn, :home, layout: false)
  end
  def calculate(conn, %{"num1" => num1, "num2" => num2, "operation" => operation}) do
    {num1, num2} =
      if operation == "*" do
        {num1, num2}
      else
        {String.to_integer(num1), String.to_integer(num2)}
      end

    result =
      case operation do
        "*" -> eval_multiply(num1, num2)
        _ -> perform_operation(num1, num2, operation)
      end
    render(conn, "result.html", result: result)
  end


  defp eval_multiply(num1, num2) when is_binary(num1) and is_binary(num2) do
    num1 = String.replace(num1, "rm", "10")
    num2 = String.replace(num2, "rm", "10")

    command = "x=#{num1}; y=$((x*#{num2})); echo $y"
    case System.cmd("bash", ["-c", command]) do
      {output, 0} -> String.trim(output)
      _ -> "Error executing command"
    end
  end



  defp perform_operation(num1, num2, operation) do
    case operation do
      "+" -> num1 + num2
      "-" -> num1 - num2
      "/" ->
        if num2 == 0 do
          "Division by zero"
        else
          div(num1, num2)
        end
      "%" ->
        if num2 == 0 do
          "Division by zero"
        else
          rem(num1, num2)
        end
      _ -> "Invalid operation"
    end
  end

end
